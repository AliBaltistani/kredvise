<?php

namespace App\Console\Commands;

use Database\Seeders\PermissionSeeder;
use Illuminate\Console\Command;

class AddCurrencyPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:add-currency';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add user currency permissions to the system';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->components->info('Adding user currency permissions...');
        
        $seeder = new PermissionSeeder();
        $seeder->run();
        
        $this->components->info('User currency permissions added successfully!');
        
        return Command::SUCCESS;
    }
}