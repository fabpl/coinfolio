<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'market_id', 'price_id', 'user_id', 'quantity', 'last'
    ];

    /**
     * Get the market that owns the order.
     */
    public function market()
    {
        return $this->belongsTo('App\Models\Market');
    }

    /**
     * Get the price that owns the order.
     */
    public function price()
    {
        return $this->belongsTo('App\Models\Price');
    }

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
