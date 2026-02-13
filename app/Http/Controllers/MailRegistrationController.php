<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Finance;
use App\Models\Financier;
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

        // Si la mairie n'existe pas ou est déjà active, on peut aussi regarder si c'est un agent de finance
        if (! $mairie) {
            $finance = Finance::where('email', $email)->where('status', 'pending')->first();
            if ($finance) {
                return view('auth.finance-complete-registration', ['email' => $email]);
            }

            $financier = Financier::where('email', $email)->where('status', 'pending')->first();
            if ($financier) {
                return view('auth.finance-complete-registration', ['email' => $email]);
            }

            return redirect()->route('login.mairie')->with('error', 'Ce lien d\'invitation est invalide ou a déjà été utilisé.');
        }

        return view('auth.mairie-complete-registration', ['email' => $email]);
    }

    public function completeRegistration(Request $request)
    {
        // 1. Validation de base
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp_code' => 'required|numeric',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 2. Vérification dans la table Mairies
        $mairie = Mairie::where('email', $request->email)
            ->where('status', 'pending')
            ->first();

        if ($mairie) {
            return $this->finalizeMairieRegistration($mairie, $request);
        }

        // 3. Vérification dans la table Finance
        $finance = Finance::where('email', $request->email)
            ->where('status', 'pending')
            ->first();

        if ($finance) {
            return $this->finalizeFinanceRegistration($finance, $request);
        }

        // 4. Vérification dans la table Financier
        $financier = Financier::where('email', $request->email)
            ->where('status', 'pending')
            ->first();

        if ($financier) {
            return $this->finalizeFinancierRegistration($financier, $request);
        }

        return back()->withErrors(['otp_code' => 'Aucune invitation en attente trouvée pour cet e-mail.'])->withInput();
    }

    protected function finalizeMairieRegistration($mairie, $request)
    {
        if ($mairie->otp_code != $request->otp_code) {
            return back()->withErrors(['otp_code' => 'Le code OTP est incorrect.'])->withInput();
        }

        if (Carbon::now()->isAfter($mairie->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'Ce code OTP a expiré.'])->withInput();
        }

        $mairie->password = Hash::make($request->password);
        $mairie->status = 'active';
        $mairie->otp_code = null;
        $mairie->otp_expires_at = null;
        $mairie->save();

        Auth::guard('mairie')->login($mairie);

        return redirect()->route('mairie.dashboard.index')->with('success', 'Votre compte mairie a été activé avec succès !');
    }

    protected function finalizeFinanceRegistration($finance, $request)
    {
        if ($finance->otp_code != $request->otp_code) {
            return back()->withErrors(['otp_code' => 'Le code OTP est incorrect.'])->withInput();
        }

        if (Carbon::now()->isAfter($finance->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'Ce code OTP a expiré.'])->withInput();
        }

        $finance->password = Hash::make($request->password);
        $finance->status = 'active';
        $finance->otp_code = null;
        $finance->otp_expires_at = null;
        $finance->save();

        Auth::guard('finance')->login($finance);

        return redirect()->route('mairie.dashboard.index')->with('success', 'Votre compte finance a été activé avec succès !');
    }

    protected function finalizeFinancierRegistration($financier, $request)
    {
        if ($financier->otp_code != $request->otp_code) {
            return back()->withErrors(['otp_code' => 'Le code OTP est incorrect.'])->withInput();
        }

        if (Carbon::now()->isAfter($financier->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'Ce code OTP a expiré.'])->withInput();
        }

        $financier->password = Hash::make($request->password);
        $financier->status = 'active';
        $financier->otp_code = null;
        $financier->otp_expires_at = null;
        $financier->save();

        // On le connecte via le guard 'finance' car il partage le même dashboard
        Auth::guard('financier')->login($financier);

        return redirect()->route('mairie.dashboard.index')->with('success', 'Votre compte responsable financier a été activé avec succès !');
    }

    // Agent Mail
    public function showCompletionFormAgent(Request $request, $email)
    {
        $agents = Agent::where('email', $email)->where('status', 'pending')->first();

        if (! $agents) {
            return redirect()->route('login.agent')->with('error', 'Ce lien d\'invitation est invalide ou a déjà été utilisé.');
        }

        return view('auth.agent-complete-registration', ['email' => $email]);
    }

    public function completeRegistrationAgent(Request $request)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:agents,email',
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
        $commercant = \App\Models\Commercant::where('email', $email)->first();

        if (! $commercant) {
            return redirect()->route('login.commercant')->with('error', 'Ce lien d\'invitation est invalide.');
        }

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
