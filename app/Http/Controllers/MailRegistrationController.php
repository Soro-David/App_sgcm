<?php
// app/Http/Controllers/MairieRegistrationController.php
namespace App\Http\Controllers;

use App\Models\Mairie;
use App\Models\Agent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class MailRegistrationController extends Controller
{
    public function showCompletionForm(Request $request, $email)
    {
        $mairie = Mairie::where('email', $email)->where('status', 'pending')->first();

        // Si la mairie n'existe pas ou est déjà active, le lien n'est plus valide
        if (!$mairie) {
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

        if (!$mairie || $mairie->otp_code != $request->otp_code) {
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
        return redirect()->route('mairie.dashboard')->with('success', 'Votre compte a été activé avec succès !');
    }

    //Agent Mail
     public function showCompletionFormAgent(Request $request, $email)
    {
        $agents = Agent::where('email', $email)->where('status', 'pending')->first();

        // Si la mairie n'existe pas ou est déjà active, le lien n'est plus valide
        if (!$agents) {
            return redirect('/')->with('error', 'Ce lien d\'invitation est invalide ou a déjà été utilisé.');
        }

        return view('auth.agent-complete-registration', ['email' => $email]);
    }

    public function completeRegistrationAgent(Request $request)
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
        $agent = Agent::where('email', $request->email)
                         ->where('status', 'pending')
                         ->first();

        if (!$agent || $agent->otp_code != $request->otp_code) {
            return back()->withErrors(['otp_code' => 'Le code OTP est incorrect.'])->withInput();
        }

        if (Carbon::now()->isAfter($agent->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'Ce code OTP a expiré.'])->withInput();
        }

        // 3. Mise à jour de la mairie
        $agent->password = Hash::make($request->password);
        $agent->status = 'active';
        $agent->otp_code = null;
        $agent->otp_expires_at = null;
        $agent->save();

        // 4. Connexion automatique de la mairie
        Auth::guard('agent')->login($agent);

        // 5. Redirection vers son tableau de bord
        return redirect()->route('agent.dashboard')->with('success', 'Votre compte a été activé avec succès !');
    }
}