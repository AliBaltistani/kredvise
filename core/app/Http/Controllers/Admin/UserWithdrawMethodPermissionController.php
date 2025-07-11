<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WithdrawMethod;
use App\Models\UserWithdrawMethodPermission;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserWithdrawMethodPermissionController extends Controller
{
    /**
     * Display withdrawal method permissions for a specific user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {   
        $user = User::findOrFail($id);
        $pageTitle = $user->username . ' - Withdrawal Method Permissions';
        
        $withdrawMethods = WithdrawMethod::orderBy('name')
            ->get();
            
        $permissions = UserWithdrawMethodPermission::where('user_id', $user->id)
            ->pluck('status', 'withdraw_method_id')
            ->toArray();
            
        return view('admin.users.withdraw_method_permissions', compact('pageTitle', 'user', 'withdrawMethods', 'permissions'));
    }
    
    /**
     * Update withdrawal method permissions for a specific user.
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
        
        foreach ($permissions as $methodId => $status) {
            UserWithdrawMethodPermission::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'withdraw_method_id' => $methodId,
                ],
                ['status' => $status]
            );
        }
        
        $notify[] = ['success', 'Withdrawal method permissions updated successfully'];
        return back()->withNotify($notify);
    }
    
    /**
     * Reset all withdrawal method permissions for a specific user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reset($id)
    {   
        $user = User::findOrFail($id);
        
        // Delete all withdrawal method permissions for this user
        UserWithdrawMethodPermission::where('user_id', $user->id)->delete();
        
        $notify[] = ['success', 'Withdrawal method permissions reset successfully'];
        return back()->withNotify($notify);
    }
}