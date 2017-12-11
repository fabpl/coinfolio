<?php

namespace App\Models;

use App\Models\Price\Coindesk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Price extends Model
{
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
        'usd', 'eur',
    ];

    /**
     * @return $this
     */
    public function synchronize()
    {
        if ($this->updated_at->diffInMinutes(Carbon::now()) >= 60) {
            $updater = new Coindesk();
            $this->fill($updater->updatePrice($this));
        }

        return $this;
    }
}
