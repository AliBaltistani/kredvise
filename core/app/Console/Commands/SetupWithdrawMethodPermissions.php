<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupWithdrawMethodPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:withdraw-method-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the withdraw method permissions feature';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting up withdraw method permissions...');

        // Run the migration to create the user_withdraw_method_permissions table
        $this->call('migrate', [
            '--path' => 'database/migrations/2024_09_03_000000_create_user_withdraw_method_permissions_table.php',
        ]);

        // Add permissions to the admin panel
        $this->call('db:seed', [
            '--class' => 'Database\\Seeders\\PermissionSeeder',
            '--force' => true,
        ]);

        // Populate initial data
        $this->call('populate:withdraw-method-permissions');

        $this->info('Withdraw method permissions setup completed successfully!');
        return Command::SUCCESS;
    }
}