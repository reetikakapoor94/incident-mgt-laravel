<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->tokens()->delete(); // optional: remove old tokens

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout()
    { 
        auth()->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }


public function getLocationByPincode($pincode)
{
    $url = "http://api.postalpincode.in/pincode/{$pincode}"; // using HTTP avoids SSL issues

    // Init cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return response()->json(['message' => 'Request failed: ' . curl_error($ch)], 500);
    }

    curl_close($ch);

    $data = json_decode($response, true);

    // Validate API response
    if (empty($data[0]['PostOffice']) || $data[0]['Status'] !== 'Success') {
        return response()->json(['message' => 'Invalid Pincode'], 404);
    }

    $postOffice = $data[0]['PostOffice'][0];

    return response()->json([
        'city'    => $postOffice['District'] ?? null,
        'state'   => $postOffice['State'] ?? null,
        'country' => $postOffice['Country'] ?? null
    ]);
}

}
