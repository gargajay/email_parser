<?php

namespace App\Http\Controllers; 

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Default response structure
    public $response = ['success' => false, 'message' => 'Something went wrong'];

    /**
     * Login user and generate access token.
     */
    public function login(Request $request)
    {
        // Validation rules
        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $this->response['message'] = $validator->errors()->first();
            return response()->json($this->response, UNPROCESSABLE_ENTITY); // Unprocessable Entity
        }

        // Attempt to authenticate user
        if (!Auth::attempt(['email'=> $request->email, 'password'=> $request->password])) {
            $this->response['message'] = 'Invalid login details';
            return response()->json($this->response, STATUS_UNAUTHORIZED); // Unauthorized
        }

        $user = User::find(Auth::id());

        // Create access token
        $user->access_token = $user->createToken($user->id . 'token')->plainTextToken;

        $this->response['success'] = true;
        $this->response['message'] = 'Login successfully.';
        $this->response['data'] = $user;

        return response()->json($this->response, STATUS_OK); // OK
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        // Validation rules
        $rules = [
            'name' => ['required', 'max:125'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $this->response['message'] = $validator->errors()->first();
            return response()->json($this->response, UNPROCESSABLE_ENTITY); // Unprocessable Entity
        }

        // Create new user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        // Create access token
        $user->access_token = $user->createToken($user->id . 'token')->plainTextToken;

        $this->response['success'] = true;
        $this->response['message'] = 'Registered successfully.';
        $this->response['data'] = $user;

        return response()->json($this->response, STATUS_CREATED); // Created
    }

    /**
     * Get authenticated user's profile.
     */
    public function getProfile(Request $request)
    {
        $user = Auth::user();
        $this->response['success'] = true;
        $this->response['message'] = 'Fetching profile info.';
        $this->response['data'] = $user;
        
        return response()->json($this->response, STATUS_OK); // OK
    }

    /**
     * Logout user and revoke tokens.
     */
    public function logout()
    {
        $user = Auth::user();

        // Revoke all tokens
        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });

        $this->response['success'] = true;
        $this->response['message'] = 'Logout successfully.';
        
        return response()->json($this->response, STATUS_OK); // OK
    }

    public function webhook(Request $request){
Log::info("webhock",['request'=>$request->all()]);
    }
}
