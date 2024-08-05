<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function login(): JsonResponse
    {   
        request()->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);
        
        $user = User::where('email', request()->email)->first();
        
        if (! $user || ! Hash::check(request()->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        /* if (!Auth::attempt(request()->only('email', 'password'))) {
            return response()->json([
              'message' => 'Unauthorized'
            ], 401);
        }   */    

        return response()->json([
            'token' => $user->createToken(request()->device_name)->plainTextToken
        ]);
    }

    public function register(): JsonResponse
    {   
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required'],
        ]);
        
        $user = User::create([
            'name' => request()->name,
            'email' => request()->email,
            'password' => Hash::make(request()->password),
        ]);

        //event(new Registered($user));

        //Auth::login($user);

        return response()->json([
            'message' => "User {$user->name} registered succesfully"
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}
