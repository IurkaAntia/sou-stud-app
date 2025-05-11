<?php
namespace App\Http\Controllers;

use App\Models\ExpoToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ExpoTokenController extends Controller
{
    public function store(Request $request)
    {

        // Check if the user is authenticated
        if (!Auth::check()) {

            return response()->json(
                ["message" => "User not authenticated"],
                401
            );
        }

        $user = Auth::user()->id;

        $request->validate([
            "token" => "required|string",
        ]);

        // Store or update the Expo token for the authenticated user
        ExpoToken::updateOrCreate(
            ["users_id" => $user],
            ["expo_token" => $request->token]
        );

        return response()->json(
            ["message" => "Expo token stored successfully"],
            200
        );
    }
}
