<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Adapters\EmailAdapter;


class AuthController extends Controller
{

    private $emailAdapter;

    public function __construct(EmailAdapter $emailAdapter)
    {
        $this->emailAdapter = $emailAdapter;
    }
    public function login(Request $request)
    {
        $validator = $this->validateRequest($request, 'login');

        if ($validator->fails()) {
            $errors = $validator->errors();
            Log::channel('api')->error('Login verification error', ['errors' => $errors]);
            return response()->json(['errors' => $errors], 422);
        } else {

            $credentials = $request->only('name', 'password');
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('authToken')->plainTextToken;
                $cookie = cookie('jwt', $token, 60 * 24);
                Log::channel('api')->info('Login successful', ['user_id' => $user->id, 'name' => $user->name]);
                return response()->json(['token' => $token], 200)->withCookie($cookie);
            } else {
                $error = ['message' => 'Login failed'];
                Log::channel('api')->warning('Login failed', ['error' => $error]);
                return response()->json(['errors' => ['message' => $error]], 401);
            }
        }
    }

    public function register(Request $request)
    {
        $validator = $this->validateRequest($request, 'register');

        if ($validator->fails()) {
            $errors = $validator->errors();
            Log::channel('api')->error('Registration error', ['errors' => $errors]);
            return response()->json(['errors' => $errors], 422);
        } else {

            $user = new User;
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->password = Hash::make($request['password']);
            $user->save();

            /*
            $to =  $request['email'];
            $subject = 'registration confirmation';
            $content = 'Your registration has been successfully completed.';
            $this->emailAdapter->sendEmail($to, $subject, $content);
            */

            Log::channel('api')->info('User saved', ['user_id' => $user->id, 'name' => $user->name]);
            return response()->json(['message' => 'User successfully registered'], 201);
        }
    }

    public function user($id)
    {


        if ($id) {
            $user = DB::table('users')->find($id);

            if (!$user) {
                Log::channel('api')->warning('Get user not found', ['user_id' => $id]);
                return response()->json(['message' => 'User not found'], 404);
            } else {
                Log::channel('api')->info('Get user information brought in', ['user_id' => $id]);
                return response()->json($user, 200);
            }
        }
    }

    public function users(Request $request)
    {
        $username = $request->query('name');
        $email = $request->query('email');

        $query = User::query();

        if ($username) {
            $query->where('name', 'LIKE', '%' . $username . '%');
        }

        if ($email) {
            $query->where('email', 'LIKE', '%' . $email . '%');
        }

        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 1);

        Log::channel('api')->info('User list brought in', ['username' => $username, 'email' => $email]);


        $users = $query->paginate($perPage, ['*'], 'page', $page);

        return $users;
    }

    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            'name' => 'required|unique:users,name,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'required',
        ]);

        $user = User::find($id);

        if (!$user) {
            Log::channel('api')->warning('Update user not found', ['user_id' => $id]);
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->id !== Auth::user()->id) {
            Log::channel('api')->warning('Update unauthorized operation', ['user_id' => $id]);
            return response()->json(['message' => 'You are not authorized for this action'], 403);
        }

        Log::channel('api')->info('User updated', ['user_id' => $id]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->save();

        return response()->json($user, 200);
    }
    public function delete($id)
    {
        $user = User::find($id);

        if (!$user) {
            Log::channel('api')->warning('Delete user not found', ['user_id' => $id]);
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->id !== Auth::user()->id) {
            Log::channel('api')->warning('Delete unauthorized operation', ['user_id' => $id]);
            return response()->json(['message' => 'You are not authorized for this action'], 403);
        }

        Log::channel('api')->info('User deleted', ['user_id' => $id]);

        $user->delete();

        return response()->json(['message' => 'User successfully deleted'], 200);
    }

    protected function validateRequest(Request $request, $type)
    {

        if ($type === 'register') {
            $rules = [
                'name' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required',
            ];
        } else {
            $rules = [
                'name' => 'required|',
                'password' => 'required',
            ];
        }

        return Validator::make($request->all(), $rules);
    }
}
