<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            \Log::info('Google OAuth callback', [
                'google_id' => $googleUser->id,
                'email' => $googleUser->email,
                'name' => $googleUser->name,
            ]);

            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                Auth::login($user);
                return $this->redirectToTenant($user);
            }

            $existingUser = User::where('email', $googleUser->email)->first();

            if ($existingUser) {
                $existingUser->update([
                    'google_id' => $googleUser->id
                ]);
                Auth::login($existingUser);
                return $this->redirectToTenant($existingUser);
            }

            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => null,
            ]);

            \Log::info('New user created', ['user_id' => $newUser->id]);
            Auth::login($newUser);
            return $this->redirectToTenant($newUser);

        } catch (Exception $e) {
            \Log::error('Google OAuth failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect('/login')->with('error', 'Something went wrong with Google authentication.');
        }
    }

    private function redirectToTenant(User $user)
    {
        $firstTenant = $user->teams()->first();

        if ($firstTenant) {
            return redirect()->route('filament.admin.pages.dashboard', ['tenant' => $firstTenant->slug]);
        }

        return redirect()->route('filament.admin.tenant-registration');
    }
}
