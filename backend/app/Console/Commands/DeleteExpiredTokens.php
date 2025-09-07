<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Sanctum\PersonalAccessToken;

class DeleteExpiredTokens extends Command
{
    protected $signature = 'tokens:delete-expired';

    protected $description = 'This command delete from db expires tokens';

    public function handle(): int
    {
        $count = PersonalAccessToken::query()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->delete();

        $this->info("Usunięto $count wygasłych tokenów.");

        return self::SUCCESS;
    }
}
