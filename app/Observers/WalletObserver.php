<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Wallet;

class WalletObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        // Create a wallet for the new user
        Wallet::create([
            'user_id' => $user->id,
            'balance' => 0, // Set an initial balance, if required
        ]);
    }

    // Other observer methods can be added here for additional events
}
