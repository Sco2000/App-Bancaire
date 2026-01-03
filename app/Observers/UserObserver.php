<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    public function creating(User $user): void
    {
        if (empty($user->{$user->getKeyName()})) {
            $user->{$user->getKeyName()} = (string) Str::uuid();
        }

        if (empty($user->numero_compte)) {
            $user->login = self::generateUniqueLogin();
        }

        // if (empty($user->password)) {
        //     $user->password = Hash::make(Str::random(10));
        // }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }

    public static function generateUniqueLogin(): string
    {
        do {
            $login = 'USER' . str_pad(mt_rand(10000, 99999), 5, '0', STR_PAD_LEFT);
        } while (User::where('login', $login)->exists());

        return $login;
    }
}
