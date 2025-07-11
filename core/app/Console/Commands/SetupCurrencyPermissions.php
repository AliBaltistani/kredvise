<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupCurrencyPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:currency-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup all currency permissions components';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->components->info('Setting up currency permissions...');
        
        // Run the migration
        $this->components->task('Running migration', function() {
            $this->call('migrate');
            return true;
        });
        
        // Add permissions
        $this->components->task('Adding permissions', function() {
            $this->call('permissions:add-currency');
            return true;
        });
        
        // Populate initial permissions
        $this->components->task('Populating initial permissions', function() {
            $this->call('permissions:populate-currency');
            return true;
        });
        
        $this->components->info('Currency permissions setup completed successfully!');
        $this->components->info('You can now manage user currency permissions from the user detail page.');
        
        return Command::SUCCESS;
    }
}