<?php

namespace App\Http\Controllers;

use App\Models\GoogleToken;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        /** @var AbstractProvider $googleProvider */
        $googleProvider = Socialite::driver('google')
            ->scopes([
                'https://www.googleapis.com/auth/drive',
                'https://www.googleapis.com/auth/drive.file',
                'https://www.googleapis.com/auth/calendar',
                'https://www.googleapis.com/auth/calendar.events',
                'openid',
                'profile',
                'email',
            ]);

        $response = $googleProvider
            ->with([
                'access_type' => 'offline',
                'prompt' => 'consent',
            ])
            ->redirect();

        // Log the actual redirect URL so we can debug redirect_uri_mismatch
        try {
            Log::info('Google redirect url: ' . $response->getTargetUrl());
        } catch (\Throwable $e) {
            Log::warning('Failed to log Google redirect url: ' . $e->getMessage());
        }

        return $response;
    }

   public function callback(): RedirectResponse
{
    try {
        $googleUser = Socialite::driver('google')->user();

        if (Auth::check()) {
            $user = Auth::user();
        } else {
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(str()->random(16)),
                ]
            );
        }

        $user->googleToken()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'google_id' => $googleUser->getId(),
                'access_token' => $googleUser->token,
                'refresh_token' => $googleUser->refreshToken,
                'expires_at' => now()->addSeconds($googleUser->expiresIn ?? 3600),
                'token_type' => 'Bearer',
            ]
        );

        Auth::login($user, true);

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Berhasil terhubung dengan Google Drive & Calendar.');
    } catch (\Exception $e) {
        Log::error('Google auth callback error: ' . $e->getMessage());

        return redirect()->route('home')
            ->with('error', 'Autentikasi Google gagal. Silakan coba lagi.');
    }
}

    public function logout(): RedirectResponse
    {
        Auth::logout();
        return redirect('/');
    }
}
