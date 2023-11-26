<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $payload = $request->validate([
            "name" => "required|min:2",
            "email" => "required|email|unique:users,email",
            "password" => "required|min:8"
        ]);

        try {
            $payload["password"] = Hash::make($payload["password"]);
            User::create($payload);
            return ["status" => 200, "message" => "User created successfully!"];
        } catch (\Exception $err) {
            Log::info("user create error => " . $err->getMessage());
            return ["status" => 500, "message" => "Somrthing went wrong!"];
        }
    }

    // * To login user
    public function login(Request $request)
    {
        $payload = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        try {
            $user = User::where("email", $payload["email"])->first();
            if (!$user) {
                return ["status" => 404, "message" => "No User found!"];
            }

            // * Generate user token
            $token = $user->createToken("web")->plainTextToken;
            $userPaload = [
                "name" => $user->name,
                "email" => $user->email,
                "token" => $token,
            ];

            return ["status" => 200, "message" => "Logged in successfully!", "user" => $userPaload];
        } catch (\Exception $err) {
            Log::info("user logged in error =>" . $err->getMessage());
            return ["status" => 500, "message" => "Something went wrong!"];
        }
    }

    // * Logout user
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return ["status" => 200, "message" => "logged out successfully!"];
        } catch (\Exception $err) {
            Log::info("user logged out error =>" . $err->getMessage());
            return ["status" => 500, "message" => "Something went wrong!"];
        }

    }
}
