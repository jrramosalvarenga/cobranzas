<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Socialite;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::withoutGlobalScopes()
            ->where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if (! $user) {
            return redirect()->route('login')
                ->with('status', 'No existe una cuenta con ese correo. Contacta al administrador.');
        }

        if (! $user->google_id) {
            $user->update(['google_id' => $googleUser->id]);
        }

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard'));
    }
}
