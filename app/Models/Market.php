<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Market extends Model
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
        'exchange_id', 'currency_id_from', 'currency_id_to', 'high', 'low', 'bid', 'ask', 'volume', 'last',
    ];

    /**
     * Get the exchange that owns the market.
     */
    public function exchange()
    {
        return $this->belongsTo('App\Models\Exchange');
    }

    /**
     * Get the exchange that owns the market.
     */
    public function currency_from()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_id_from');
    }

    /**
     * Get the exchange that owns the market.
     */
    public function currency_to()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_id_to');
    }

    /**
     * @return $this
     */
    public function synchronize()
    {
        if ($this->updated_at->diffInMinutes(Carbon::now()) >= 10) {
            $exchange = $this->exchange()->first();

            $class = 'App\Models\Exchange\\' . ucfirst($exchange->name);
            if (class_exists($class)) {
                $updater = new $class();
                $this->fill($updater->updateMarket($this));
            }
        }

       return $this;
    }

    /**
     * @param string $code
     * @return bool
     */
    public function isFromBitcoin($code)
    {
        return in_array($code, array('BTC', 'XBT'));
    }
}
