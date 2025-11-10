<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Secretary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SecretaryAuthController extends Controller
{
    // Afficher le formulaire de connexion
    public function showLoginForm()
    {
        return view('auth.secretary-login');
    }

    // Traiter la connexion
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $secretary = Secretary::where('email', $request->email)->first();

        if (!$secretary || !Hash::check($request->password, $secretary->password)) {
            throw ValidationException::withMessages([
                'email' => 'Les identifiants sont incorrects.'
            ]);
        }

        if (!$secretary->is_active) {
            throw ValidationException::withMessages([
                'email' => 'Votre compte est désactivé.'
            ]);
        }

        Auth::guard('secretary')->login($secretary);

        return redirect()->route('dashboard')->with('success', 'Connexion réussie!');
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::guard('secretary')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('secretary.login');
    }
}