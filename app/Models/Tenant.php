<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;

class Tenant extends Model
{
    use Billable;

    protected $fillable = [
        'name',
        'slug',
        'owner_id'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function taxRates()
    {
        return [];
    }

    /**
     * Sync tax rates (NO-OP)
     */
    public function syncTaxRates()
    {
        return $this;
    }
}
