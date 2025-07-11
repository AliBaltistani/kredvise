<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        // User Currency Permissions
        $this->create('View User Currency Permissions', 'UserCurrencyPermissionController', 'admin.users.currency.permissions');
        $this->create('Update User Currency Permissions', 'UserCurrencyPermissionController', 'admin.users.currency.permissions.update');
        $this->create('Reset User Currency Permissions', 'UserCurrencyPermissionController', 'admin.users.currency.permissions.reset');
        
        // User Withdraw Method Permissions
        $this->create('View User Withdraw Method Permissions', 'UserWithdrawMethodPermissionController', 'admin.users.withdraw.method.permissions');
        $this->create('Update User Withdraw Method Permissions', 'UserWithdrawMethodPermissionController', 'admin.users.withdraw.method.permissions.update');
        $this->create('Reset User Withdraw Method Permissions', 'UserWithdrawMethodPermissionController', 'admin.users.withdraw.method.permissions.reset');
    }

    private function create($name, $controller, $code)
    {
        Permission::firstOrCreate([
            'name' => $name,
            'controller' => $controller,
            'code' => $code
        ]);
    }
}