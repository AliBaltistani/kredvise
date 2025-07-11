<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCurrencyPermission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'gateway_currency_id',
        'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get the user that owns the currency permission.
     */
    public function user()
    {   
        return $this->belongsTo(User::class);
    }

    /**
     * Get the gateway currency associated with the permission.
     */
    public function gatewayCurrency()
    {   
        return $this->belongsTo(GatewayCurrency::class);
    }

    /**
     * Scope a query to only include active permissions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope a query to only include inactive permissions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }
}