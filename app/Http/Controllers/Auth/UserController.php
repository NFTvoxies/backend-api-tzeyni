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
    public function register(RegisterRequest $request){
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
        Mail::to($user->email)->send(new VerificationMail($user->full_name,$user->code));
        return response()->json(['status' => true, 'message' => 'Le code dans votre boite mail pour verifier t\'adresse email ','token'=>$token],200);
    }

    // Sign IN Function 
    public function login(LoginRequest $request){
        $user = User::where('email',$request->email)->first();
        if( !$user || !Hash::check($request->password,$user->password)){
            return response()->json(['status' => false,'message'=>'Mote de passe est incorrect'],200);
        }
        $token = $user->createToken('user-token')->plainTextToken;
        return response()->json(['status' => true, 'message'=>'Bienvenue','token'=>$token],200);
    }

    // Verify Email Function 
    public function verify(CodeRequest $request) {
        if(Auth::guard('user')->check()){
            $user = Auth::guard('user')->user();
            if( $user->code != $request->post('code') ){
                return response()->json(['status' => false,'message' => 'Le code est incorrect'],401);
            }
            $user->email_verified_at = now();
            $user->save();
            return response()->json(['status' => true,'message' => 'Votre Addresse Email est Verifie','data'=>$user], 200);
        }
        return response()->json(['message' => 'Utilisateur non authentifié'], 401);
    }

    // Get Info Of User 
    public function edit(){
        $user = Auth::guard('user')->user()->makeHidden(['code']);
        return response()->json(['status' => true, 'message' => 'Voici Votre Profile', 'data' => $user],200);
    }

    //Update profile Function 
    public function update(UpdateRequest $request){
        $user = Auth::guard('user')->user()->makeHidden(['code']);
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->phone = $request->phone;
        $user->city = $request->city;
        $user->addresse = $request->addresse;
        $user->save();
        return response()->json([
            'message' => 'Profile mise à jour avec succès.',
            'data' => $user
        ], 200);
    }

    // Logout Function
    public function logout() {
        if (!Auth::guard('user')->check()) {
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }
        $user = Auth::guard('user')->user();
        $user->currentAccessToken()->delete();
        return response()->json(['status' => true,'message' => 'Déconnexion réussie'], 200);
    }
}
