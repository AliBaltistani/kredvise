<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GatewayCurrency;
use App\Models\User;
use App\Models\UserCurrencyPermission;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserCurrencyPermissionController extends Controller
{
    /**
     * Display currency permissions for a specific user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {   
        $user = User::findOrFail($id);
        $pageTitle = $user->username . ' - Currency Permissions';
        
        $gatewayCurrencies = GatewayCurrency::with('method')
            ->orderBy('name')
            ->get();
            
        $permissions = UserCurrencyPermission::where('user_id', $user->id)
            ->pluck('status', 'gateway_currency_id')
            ->toArray();
            
        return view('admin.users.currency_permissions', compact('pageTitle', 'user', 'gatewayCurrencies', 'permissions'));
    }
    
    /**
     * Update currency permissions for a specific user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
        $user = User::findOrFail($id);
        
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'required|in:0,1',
        ]);
        
        $permissions = $request->permissions;
        
        foreach ($permissions as $currencyId => $status) {
            UserCurrencyPermission::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'gateway_currency_id' => $currencyId,
                ],
                ['status' => $status]
            );
        }
        
        $notify[] = ['success', 'Currency permissions updated successfully'];
        return back()->withNotify($notify);
    }
    
    /**
     * Reset all currency permissions for a specific user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reset($id)
    {   
        $user = User::findOrFail($id);
        
        // Delete all currency permissions for this user
        UserCurrencyPermission::where('user_id', $user->id)->delete();
        
        $notify[] = ['success', 'Currency permissions reset successfully'];
        return back()->withNotify($notify);
    }
}