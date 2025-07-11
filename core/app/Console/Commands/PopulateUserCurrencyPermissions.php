<?php

namespace App\Console\Commands;

use App\Models\GatewayCurrency;
use App\Models\User;
use App\Models\UserCurrencyPermission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateUserCurrencyPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:populate-currency {--reset : Reset all existing permissions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate currency permissions for all existing users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $reset = $this->option('reset');
        
        if ($reset) {
            if ($this->components->confirm('Are you sure you want to reset all existing currency permissions?', false)) {
                DB::table('user_currency_permissions')->truncate();
                $this->components->info('All existing currency permissions have been reset.');
            } else {
                $this->components->info('Operation cancelled.');
                return Command::SUCCESS;
            }
        }
        
        $users = User::all();
        $currencies = GatewayCurrency::where('status', 1)->get();
        
        $bar = $this->output->createProgressBar(count($users));
        $bar->start();
        
        $count = 0;
        
        foreach ($users as $user) {
            foreach ($currencies as $currency) {
                $exists = UserCurrencyPermission::where('user_id', $user->id)
                    ->where('gateway_currency_id', $currency->id)
                    ->exists();
                    
                if (!$exists) {
                    UserCurrencyPermission::create([
                        'user_id' => $user->id,
                        'gateway_currency_id' => $currency->id,
                        'status' => 1 // Enable all currencies by default
                    ]);
                    $count++;
                }
            }
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->components->info("Created $count currency permissions for {$users->count()} users.");
        
        return Command::SUCCESS;
    }
}