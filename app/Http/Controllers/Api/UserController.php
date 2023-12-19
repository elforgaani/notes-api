<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{


    // Create User Function 
    public function signUp(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users'],
            'password' => 'required|min:8'
        ]);

        try {
            if (User::create($credentials)) {
                return response()->json([
                    'success' => true,
                    'message' => 'User created successfully'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,

                'message' => $e->getMessage()
            ]);
        }
    }

    // Login User Function

    public function logIn(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
        try {
            if (Auth::attempt($credentials)) {
                $user = user::where('email', $request->email)->first();
                $token = $user->createToken('api-token')->plainTextToken;
                return response()->json([
                    'status' => 'success',
                    'message' => 'User Logged in successfully',
                    'user' => $user->only('id', 'name', 'email'),
                    'token' => $token
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid Credentials'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'Something went wrong',
                'message' => $e->getMessage()
            ]);
        }
    }


    // Logout User Function
    public function logOut(Request $request)
    {
        try {
            if (Auth::logout()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'User Logged out successfully'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'something went wrong'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }



    // Check Auth User Function
    public function checkAuth(Request $request)
    {
        try {
            if (Auth::check()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'User is Authenticated'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User is not Authenticated'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
