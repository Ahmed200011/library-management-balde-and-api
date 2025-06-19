<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();
        if ($validatedData) {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);
        }
        $user->addRole('user');
        $user->token_name = 'register_token';
        return ApiResponse::sendResponse(201, 'User registered successfully', new UserResource($user));
    }



    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return ApiResponse::sendResponse(422, 'Fail to login, please try again', $validator->errors()->all());
        }

        $userData = $request->only('email', 'password');
        if (Auth::attempt($userData)) {
            $user = Auth::user();
            $user->token_name = 'login_token';
            return ApiResponse::sendResponse(201, 'User login  successfully', new UserResource($user));
        } else {
            return ApiResponse::sendResponse(401, 'Unauthorized ,Email or password is not correct ', []);
        }
    }
    public function logout(Request $request)
    {
        $user = $request->user();
        // dd($user);
        if (!$user) {
            return ApiResponse::sendResponse(401, 'Unauthorized', []);
        }

        $request->user()->currentAccessToken()->delete();
        return ApiResponse::sendResponse(200, 'Logout successful', []);
    }
}
