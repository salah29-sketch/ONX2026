<?php

namespace App\Services;

use App\Models\Booking\Booking;
use App\Models\Booking\BookingAccessToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingTokenService
{
    public function hashPlaintext(string $plaintext): string
    {
        return hash('sha256', $plaintext);
    }

    /**
     * Create a new access token row; returns the plaintext secret (show once to the user).
     */
    public function issue(Booking $booking, string $purpose, \DateTimeInterface|string $expiresAt): string
    {
        $plaintext = bin2hex(random_bytes(32));

        BookingAccessToken::create([
            'booking_id'  => $booking->id,
            'token_hash'  => $this->hashPlaintext($plaintext),
            'purpose'     => $purpose,
            'expires_at'  => Carbon::parse($expiresAt),
        ]);

        return $plaintext;
    }

    /**
     * Replace existing tokens of the same purpose (e.g. one active confirmation link).
     */
    public function issueReplacing(Booking $booking, string $purpose, \DateTimeInterface|string $expiresAt): string
    {
        return DB::transaction(function () use ($booking, $purpose, $expiresAt) {
            BookingAccessToken::where('booking_id', $booking->id)
                ->where('purpose', $purpose)
                ->delete();

            return $this->issue($booking, $purpose, $expiresAt);
        });
    }

    public function verify(string $plaintext, string $purpose): ?Booking
    {
        if ($plaintext === '') {
            return null;
        }

        $hash = $this->hashPlaintext($plaintext);

        $row = BookingAccessToken::query()
            ->where('token_hash', $hash)
            ->where('purpose', $purpose)
            ->where('expires_at', '>', now())
            ->first();

        return $row?->booking;
    }

    /**
     * Invalidate a password-setup token after successful password save.
     */
    public function revokePasswordSetupToken(string $plaintext): void
    {
        BookingAccessToken::query()
            ->where('token_hash', $this->hashPlaintext($plaintext))
            ->where('purpose', BookingAccessToken::PURPOSE_PASSWORD_SETUP)
            ->delete();
    }
}
