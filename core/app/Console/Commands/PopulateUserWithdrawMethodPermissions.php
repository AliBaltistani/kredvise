<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\WithdrawMethod;
use App\Models\UserWithdrawMethodPermission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateUserWithdrawMethodPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:withdraw-method-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate initial data for user withdraw method permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Populating user withdraw method permissions...');

        // Get all users and withdraw methods
        $users = User::all();
        $withdrawMethods = WithdrawMethod::all();

        if ($withdrawMethods->isEmpty()) {
            $this->info('No withdraw methods found. Skipping population.');
            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        DB::beginTransaction();

        try {
            foreach ($users as $user) {
                // For each user, create a permission entry for each withdraw method
                // By default, all permissions are enabled (status = 1)
                foreach ($withdrawMethods as $method) {
                    UserWithdrawMethodPermission::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'withdraw_method_id' => $method->id,
                        ],
                        [
                            'status' => 1, // Enabled by default
                        ]
                    );
                }

                $bar->advance();
            }

            DB::commit();
            $bar->finish();
            $this->newLine();
            $this->info('User withdraw method permissions populated successfully!');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error populating user withdraw method permissions: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}