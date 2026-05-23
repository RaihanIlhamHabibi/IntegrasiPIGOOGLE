<?php

namespace App\Services\Concerns;

use App\Models\User;
use Google\Client;
use Illuminate\Support\Facades\Log;

trait ManagesGoogleClient
{
    protected function makeGoogleClient(User $user, array $scopes): Client
    {
        $client = new Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect_uri'));
        $client->setScopes($scopes);
        $client->setAccessType('offline');

        $token = $user->googleToken;

        if (!$token) {
            return $client;
        }

        $client->setAccessToken($this->tokenPayload($token));

        if ($client->isAccessTokenExpired() && $token->refresh_token) {
            try {
                $client->fetchAccessTokenWithRefreshToken($token->refresh_token);
                $this->persistRefreshedToken($user, $client);
            } catch (\Exception $e) {
                Log::error('Google token refresh failed: ' . $e->getMessage());
            }
        }

        return $client;
    }

    protected function tokenPayload($token): array
    {
        $expiresIn = 3600;

        if ($token->expires_at) {
            $expiresIn = max(0, $token->expires_at->getTimestamp() - now()->getTimestamp());
        }

        return array_filter([
            'access_token' => $token->access_token,
            'refresh_token' => $token->refresh_token,
            'expires_in' => $expiresIn,
            'token_type' => $token->token_type ?? 'Bearer',
            'created' => $token->updated_at?->timestamp ?? time(),
        ]);
    }

    protected function persistRefreshedToken(User $user, Client $client): void
    {
        $tokenData = $client->getAccessToken();

        if (!is_array($tokenData) || empty($tokenData['access_token'])) {
            return;
        }

        $user->googleToken()->update([
            'access_token' => $tokenData['access_token'],
            'refresh_token' => $tokenData['refresh_token'] ?? $user->googleToken->refresh_token,
            'expires_at' => now()->addSeconds($tokenData['expires_in'] ?? 3600),
        ]);

        $user->unsetRelation('googleToken');
    }
}
