<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class EmailCheckController extends Controller
{
    /**
     * Check if an email exists in the database
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkEmail(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if the email exists in the database
        $exists = User::where('email', $request->email)->exists();

        // Return JSON response
        return response()->json([
            'exists' => $exists
        ]);
    }
} 