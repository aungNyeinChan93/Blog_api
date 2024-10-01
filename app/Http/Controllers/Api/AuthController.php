<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\RequestStack;

class AuthController extends Controller
{
    // user register
    public function register(Request $request)
    {

        try {
            $validated = $request->validate([
                "name" => "required",
                "email" => "required",
                "password" => "required",
            ]);

            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
            ]);

            $token = $user->createToken('register')->plainTextToken;

            if ($token) {
                return ResponseHelper::success(["token" => $token, "user" => $user]);
            }
        } catch (Exception $err) {
            return response()->json([
                "error" => $err->getMessage(),
            ], 422);
        }
    }

    // user Login
    public function login(Request $request)
    {
        $validated = $request->validate([
            "email" => "required",
            "password" => "required"
        ]);

        if (Auth::attempt($validated)) {
            $user = User::where("email", $request->email)->first();
            $token = $user->createToken("login")->plainTextToken;
        }
        if ($token) {
            return ResponseHelper::success(["token" => $token, "user" => $user],"Login success");
        }
    }

    // logout
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return ResponseHelper::success([],"successfully logout");
    }
}
