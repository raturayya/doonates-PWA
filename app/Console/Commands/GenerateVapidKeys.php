<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Minishlink\WebPush\VAPID;

class GenerateVapidKeys extends Command
{
    protected $signature   = 'webpush:vapid';
    protected $description = 'Generate VAPID public/private key pair for Web Push and write to .env';

    public function handle(): int
    {
        $keys = VAPID::createVapidKeys();

        $this->writeToEnv('VAPID_PUBLIC_KEY',  $keys['publicKey']);
        $this->writeToEnv('VAPID_PRIVATE_KEY', $keys['privateKey']);

        $this->info('VAPID keys generated and written to .env');
        $this->line('Public key:  ' . $keys['publicKey']);
        $this->line('Private key: ' . $keys['privateKey']);

        return self::SUCCESS;
    }

    private function writeToEnv(string $key, string $value): void
    {
        $envPath    = base_path('.env');
        $envContent = file_get_contents($envPath);

        if (str_contains($envContent, "{$key}=")) {
            // Replace existing line
            $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
        } else {
            // Append new line
            $envContent .= PHP_EOL . "{$key}={$value}";
        }

        file_put_contents($envPath, $envContent);
    }
}
