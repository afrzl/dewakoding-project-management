<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{

    private function getGoogleDriver()
    {
        return Socialite::buildProvider(
            \Laravel\Socialite\Two\GoogleProvider::class,
            [
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'redirect' => config('services.google.redirect'),
                'guzzle' => [
                    'verify' => '/etc/ssl/certs/ca-certificates.crt',
                    'timeout' => 30,
                ],
            ]
        );
    }

    public function redirectToGoogle()
    {
        return $this->getGoogleDriver()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = $this->getGoogleDriver()->user();

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

        // Gunakan Filament helper untuk mendapatkan tenant registration URL
        return redirect(\Filament\Facades\Filament::getPanel('admin')->getTenantRegistrationUrl());
    }
}
