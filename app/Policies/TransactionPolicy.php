<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Transaction;

class TransactionPolicy
{
    public function view(User $user, Transaction $transaction)
    {
        return $transaction->user_id === $user->id;
    }
}
