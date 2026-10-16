<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Cart\Models\Cart;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('carts:delete-abandoned {--days=5 : Age in days to consider a guest cart abandoned}')]
#[Description('Deletes Guest carts which are not updated for a given number of days')]
class DeleteAbandonedCart extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = max(1, (int) $this->option('days'));
        $cutoff = now()->subDays($days);

        $deleted = Cart::whereNull('user_id')
            ->where('updated_at', '<', $cutoff)
            ->delete();

        $this->info("Pruned {$deleted} abandoned guest cart(s) older than {$days} day(s).");

        return self::SUCCESS;
    }
}
