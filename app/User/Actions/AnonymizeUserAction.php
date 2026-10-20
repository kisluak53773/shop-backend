<?php

declare(strict_types=1);

namespace App\User\Actions;

use App\Order\Models\Order;
use App\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AnonymizeUserAction
{
    public function execute(User $user): void
    {
        DB::transaction(function () use ($user) {
            $anonymousEmail = "deleted_{$user->id}_" . Str::random(4) . "@example.com";
            $anonymousName = "Deleted User";

            $user->update([
                'email' => $anonymousEmail,
                'name' => $anonymousName,
                'password' => bcrypt(Str::random(32))
            ]);

            Order::where('user_id', $user->id)->update([
                'customer_name' => $anonymousName,
                'customer_email' => $anonymousEmail,
                'customer_phone' => null,
                'customer_address' => null,
                'customer_city' => null,
                'customer_index' => null,
            ]);

            $user->tokens()->delete();
            $user->delete();
        });
    }
}