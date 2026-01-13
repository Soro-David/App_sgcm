<?php

// app/Http/Controllers/MairieRegistrationController.php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Mairie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MailRegistrationController extends Controller
{
    public function showCompletionForm(Request $request, $email)
    {
        $mairie = Mairie::where('email', $email)->where('status', 'pending')->first();

        // Si la mairie n'existe pas ou est déjà active, le lien n'est plus valide
        if (! $mairie) {
            return redirect('/')->with('error', 'Ce lien d\'invitation est invalide ou a déjà été utilisé.');
        }

        return view('auth.mairie-complete-registration', ['email' => $email]);
    }

    public function completeRegistration(Request $request)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:mairies,email',
            'otp_code' => 'required|numeric',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 2. Vérification de l'OTP
        $mairie = Mairie::where('email', $request->email)
            ->where('status', 'pending')
            ->first();

        if (! $mairie || $mairie->otp_code != $request->otp_code) {
            return back()->withErrors(['otp_code' => 'Le code OTP est incorrect.'])->withInput();
        }

        if (Carbon::now()->isAfter($mairie->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'Ce code OTP a expiré.'])->withInput();
        }

        // 3. Mise à jour de la mairie
        $mairie->password = Hash::make($request->password);
        $mairie->status = 'active';
        $mairie->otp_code = null;
        $mairie->otp_expires_at = null;
        $mairie->save();

        // 4. Connexion automatique de la mairie
        Auth::guard('mairie')->login($mairie);

        // 5. Redirection vers son tableau de bord
        return redirect()->route('mairie.dashboard.index')->with('success', 'Votre compte a été activé avec succès !');
    }

    // Agent Mail
    public function showCompletionFormAgent(Request $request, $email)
    {
        $agents = Agent::where('email', $email)->where('status', 'pending')->first();

        // Si la mairie n'existe pas ou est déjà active, le lien n'est plus valide
        if (! $agents) {
            return redirect('/')->with('error', 'Ce lien d\'invitation est invalide ou a déjà été utilisé.');
        }

        return view('auth.agent-complete-registration', ['email' => $email]);
    }

    public function completeRegistrationAgent(Request $request)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:agents,email', // Correction ici: table agents
            'otp_code' => 'required|numeric',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 2. Vérification de l'OTP
        $agent = Agent::where('email', $request->email)
            ->where('status', 'pending')
            ->first();

        if (! $agent || $agent->otp_code != $request->otp_code) {
            return back()->withErrors(['otp_code' => 'Le code OTP est incorrect.'])->withInput();
        }

        if (Carbon::now()->isAfter($agent->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'Ce code OTP a expiré.'])->withInput();
        }

        // 3. Mise à jour de l'agent
        $agent->password = Hash::make($request->password);
        $agent->status = 'active';
        $agent->otp_code = null;
        $agent->otp_expires_at = null;
        $agent->save();

        // 4. Connexion automatique de l'agent
        Auth::guard('agent')->login($agent);

        // 5. Redirection vers son tableau de bord
        return redirect()->route('agent.dashboard')->with('success', 'Votre compte a été activé avec succès !');
    }

    // Commercant Mail
    public function showCompletionFormCommercant(Request $request, $email)
    {
        // On vérifie si un commerçant avec cet email existe
        // Note: Le statut n'est peut-être pas initialisé à 'pending' pour les commerçants existants,
        // donc on vérifie simplement l'email et si le mot de passe est encore vide (ou une logique spécifique si besoin)
        $commercant = \App\Models\Commercant::where('email', $email)->first();

        if (! $commercant) {
            return redirect('/')->with('error', 'Ce lien d\'invitation est invalide.');
        }

        // On réutilise la vue agent ou on en crée une spécifique 'auth.commercant-complete-registration'
        // Pour l'instant, faisons une vue spécifique
        return view('auth.commercant-complete-registration', ['email' => $email]);
    }

    public function completeRegistrationCommercant(Request $request)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:commercants,email',
            'otp_code' => 'required|numeric',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 2. Vérification de l'OTP
        $commercant = \App\Models\Commercant::where('email', $request->email)->first();

        if (! $commercant || $commercant->otp_code != $request->otp_code) {
            return back()->withErrors(['otp_code' => 'Le code OTP est incorrect.'])->withInput();
        }

        if (Carbon::now()->isAfter($commercant->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'Ce code OTP a expiré.'])->withInput();
        }

        // 3. Mise à jour du commerçant
        $commercant->password = Hash::make($request->password);
        // $commercant->status = 'active'; // Si vous avez un champ status
        $commercant->otp_code = null;
        $commercant->otp_expires_at = null;
        $commercant->save();

        // 4. Connexion automatique
        Auth::guard('commercant')->login($commercant);
        $commercant->update(['last_activity' => now()]);

        // 5. Redirection vers son tableau de bord
        return redirect()->route('commercant.dashboard')->with('success', 'Votre compte a été activé avec succès !');
    }
}
