<?php

namespace App\Http\Controllers\Auth;

use App\Models\Professional;
use App\Http\Controllers\Controller;
use App\Mail\Professional\ResetMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\Professional\VerificationMail;
use App\Http\Requests\Professional\Auth\CodeRequest;
use App\Http\Requests\Professional\Auth\LoginRequest;
use App\Http\Requests\Professional\Auth\ResetRequest;
use App\Http\Requests\Professional\Auth\UpdateRequest;
use App\Http\Requests\Professional\Auth\RegisterRequest;

class ProfessionalController extends Controller
{
    //Sign Up Function 
    public function register(RegisterRequest $request){
        // Create a new professional account
        $professional = new Professional();
        $professional->full_name = $request->full_name;
        $professional->gender = $request->gender;
        $professional->email = $request->email;
        $professional->phone = $request->phone;
        $professional->city = $request->city;
        $professional->addresse = $request->addresse;
        $professional->experience = $request->experience;
        $professional->password = Hash::make($request->password);
        $professional->code = rand(100000, 999999); // Verification code
        $professional->save();
        // Create token
        $token = $professional->createToken('professional-token')->plainTextToken;
        Mail::to($professional->email)->send(new VerificationMail($professional->full_name,$professional->code));
        return response()->json(['status' => true, 'message' => 'Le code dans votre boite mail pour verifier t\'adresse email ','token'=>$token],200);
    }

    // Sign In Function 
    public function login(LoginRequest $request){
        $professional = Professional::where('email',$request->email)->first();
        if($professional->email_verified_at == null){
            return response()->json(['status' => false,'message' => 'Votre Adresse Email n\'a pas verifie, Verifier Votre Email'], 401);
        }
        if(!$professional || !Hash::check($request->password,$professional->password)) {
            return response()->json(['status' => false,'message' => 'Le mot de passe est incorrect'], 401);
        }
        $token = $professional->createToken('professional-token')->plainTextToken;
        return response()->json(['status' => true,'message' => 'Bienvenue' , 'token' => $token], 200);
    }

    // Verify Email Function 
    public function verify(CodeRequest $request) {
        if(Auth::guard('professional')->check()){
            $professional = Auth::guard('professional')->user();
            if( $professional->code != $request->post('code') ){
                return response()->json(['status' => false,'message' => 'Le code est incorrect'],401);
            }
            $professional->email_verified_at = now();
            $professional->save();
            return response()->json(['status' => true,'message' => 'Votre Addresse Email est Verifie','data'=>$professional], 200);
        }
        return response()->json(['message' => 'Utilisateur non authentifié'], 401);       
    }

    // Reset Password Function 
    public function reset(ResetRequest $request){
        $professional = Professional::where('email',$request->post('email'))->first();
        $newPassword  = $professional->full_name.rand(1000,9999999);
        $professional->password =  Hash::make($newPassword);
        $professional->save();
        Mail::to($professional->email)->send(new ResetMail($newPassword,$professional->full_name));
        return response()->json(['status' => true,'message' => 'Votre nouveau mot de passe est dans ta boite mail'], 200);
    }
    // Edit Profile Function 
    public function edit(){
        $professional = Auth::guard('professional')->user()->makeHidden(['code']);
        return response()->json(['status'=>true,'message'=>'Voici Votre Profile','data'=>$professional],200);
    }
    
    // Update Profile Function 
    public function update(UpdateRequest $request){       
        $professional = Auth::guard('professional')->user()->makeHidden(['code']);
        $validated = $request->validated();
        // if the professional download new picture for profile
        if ($request->hasFile('profile')) {
            if ($professional->profile) {
                Storage::disk('public')->delete($professional->profile);
            }
            $profilePath = $request->file('profile')->store('assets/profiles', 'public');
            $validated['profile'] = $profilePath;
        }
        if($validated['card_ID'] != null) {
            $professional->is_verify = true;
        }
        $professional->update($validated);
        return response()->json([
            'message' => 'Profile mise à jour avec succès.',
            'data' => $professional
        ], 200);
        
    }

    //Logout Function 
    public function logout(){
        if (!Auth::guard('professional')->check()) {
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }
        $professional = Auth::guard('professional')->user();
        $professional->currentAccessToken()->delete();
        return response()->json(['status' => true,'message' => 'Déconnexion réussie'], 200);
    }
}
