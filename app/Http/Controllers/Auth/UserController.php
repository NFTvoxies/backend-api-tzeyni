<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\Professional\VerificationMail;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Http\Requests\User\Auth\RegisterRequest;
use App\Http\Requests\Professional\Auth\CodeRequest;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Http\Requests\User\Auth\UpdateRequest;

class UserController extends Controller
{
    // Sign Up Function
    public function register(RegisterRequest $request)
    {
        // create user
        $user = new User();
        $user->full_name = $request->full_name;
        $user->gender = $request->gender;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->city = $request->city;
        $user->addresse = $request->addresse;
        $user->password = Hash::make($request->password);
        $user->code = rand(100000, 999999); // Verification code
        $user->save();
        // Create token
        $token = $user->createToken('user-token')->plainTextToken;
        Mail::to($user->email)->send(new VerificationMail($user->full_name, $user->code));
        return response()->json(['status' => true, 'message' => 'Le code dans votre boite mail pour verifier t\'adresse email ', 'token' => $token], 200);
    }

    // Sign IN Function
    public function login(LoginRequest $request)
    {
        // Find the user by email
        $user = User::where('email', $request->email)->first();

        // Check if the user exists and the password matches
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => false, 'message' => 'Le mot de passe est incorrect ou l\'utilisateur n\'existe pas.'], 200);
        }

        // Check if the email has been verified
        if ($user->email_verified_at === null) {
            return response()->json(['status' => false, 'message' => 'Veuillez vérifier votre adresse e-mail avant de vous connecter.'], 401);
        }

        // If the email is verified, generate a token and log the user in
        $token = $user->createToken('user-token')->plainTextToken;

        return response()->json(['status' => true, 'message' => 'Bienvenue', 'token' => $token, 'role' => 'client'], 200);
    }

    // Verify Email Function
    public function verify(CodeRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        // Check if user exists and the code is correct
        if (!$user || $user->code != $request->post('code')) {
            return response()->json(['status' => false, 'message' => 'Le code de vérification est incorrect'], 401);
        }

        // Mark email as verified
        $user->email_verified_at = now();
        $user->save();

        return response()->json(['status' => true, 'message' => 'Votre adresse e-mail a été vérifiée.'], 200);
    }

    // Get Info Of User
    public function edit()
    {
        $user = Auth::guard('user')->user()->makeHidden(['code']);
        return response()->json(['status' => true, 'message' => 'Voici Votre Profile', 'data' => $user], 200);
    }

    //Update profile Function
    public function update(UpdateRequest $request)
    {
        $user = Auth::guard('user')->user()->makeHidden(['code']);
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->phone = $request->phone;
        $user->city = $request->city;
        $user->addresse = $request->addresse;
        $user->save();
        return response()->json([
            'message' => 'Profil actualisé avec succès.',
            'data' => $user
        ], 200);
    }

    // Logout Function
    public function logout()
    {
        if (!Auth::guard('user')->check()) {
            return response()->json(['message' => 'Accès refusé : utilisateur non authentifié.'], 401);
        }
        $user = Auth::guard('user')->user();
        $user->currentAccessToken()->delete();
        return response()->json(['status' => true, 'message' => 'Déconnexion réussie'], 200);
    }
}
