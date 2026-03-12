<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commercant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CommercantController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'num_commerce' => 'required',
            'mot_de_passe' => 'required',
        ]);

        $commercant = Commercant::where('num_commerce', $request->num_commerce)->first();

        if (!$commercant || !Hash::check($request->mot_de_passe, $commercant->mot_de_passe)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        $token = $commercant->createToken('commercant-token', ['commercant'])->plainTextToken;

        $commercant->update(['last_activity' => now()]);

        return response()->json([
            'token' => $token,
            'commercant' => $commercant
        ]);
    }

    public function definePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:commercants,email',
            'otp_code' => 'required|numeric',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $commercant = Commercant::where('email', $request->email)->first();

        if (!$commercant || $commercant->otp_code != $request->otp_code) {
            return response()->json(['success' => false, 'message' => 'Le code OTP est incorrect.'], 422);
        }

        if (Carbon::now()->isAfter($commercant->otp_expires_at)) {
            return response()->json(['success' => false, 'message' => 'Ce code OTP a expiré.'], 422);
        }

        $commercant->password = Hash::make($request->password);
        $commercant->otp_code = null;
        $commercant->otp_expires_at = null;
        $commercant->save();

        $token = $commercant->createToken('commercant-token', ['commercant'])->plainTextToken;
        $commercant->update(['last_activity' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Votre mot de passe a été défini avec succès. Vous êtes maintenant connecté.',
            'token' => $token,
            'data' => [
                'id' => $commercant->id,
                'num_commerce' => $commercant->num_commerce,
                'nom' => $commercant->nom,
                'email' => $commercant->email,
            ]
        ], 200);
    }
    
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnexion réussie']);
    }
}