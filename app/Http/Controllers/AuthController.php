<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\ExpoToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function getUser(Request $request)
    {
        return response()->json($request->user());
    }
    public function login(Request $request)
    {
        $request->validate([
            "personal_number" => "required",
            "password" => "required",
        ]);

        $user = User::where(
            "personal_number",
            $request->personal_number
        )->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(["message" => "Invalid credentials"], 401);
        }

        if ($user->tokens()->exists()) {
            return response()->json(
                [
                    "message" =>
                        "You are already logged in from another device.",
                ],
                403
            );
        }

        $token = $user->createToken("Personal_Access_Token")->plainTextToken;

        return response()->json([
            "user" => $user,
            "token" => $token,
        ]);
    }

    public function logout()
    {
        $user = Auth::user();

        if ($user) {
            $user->currentAccessToken()->delete();

            ExpoToken::where("users_id", $user->id)->delete();

            return response()->json(["message" => "Logged out successfully"]);
        }

        return response()->json(["message" => "Unable to log out"], 400);
    }
}
