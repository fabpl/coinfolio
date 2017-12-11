<?php

namespace App\Http\Controllers;

use App\Models\Exchenge\Bittrex;
use App\Models\Order;
use App\Models\Price;
use App\Models\Price\Coindesk;
use App\Models\Market;
use Illuminate\Support\Facades\Auth;

class MarketsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the market view.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Market
        $market = Market::findOrFail($id)->synchronize();

        // Price
        $price = Price::first()->synchronize();

        // Orders
        $orders = Order::where('user_id', Auth::user()->id)
            ->where('market_id', $market->id)
            ->orderBy('created_at', 'desc')
            ->with(['price'])
            ->get();

        // Histories
        $histories = array('all' => array(), 'latest' => array());

        $histories['all'] = Market::withTrashed()
            ->where('exchenge_id', $market->exchenge_id)
            ->where('currency_id_from', $market->currency_id_from)
            ->where('currency_id_to', $market->currency_id_to)
            ->orderBy('created_at', 'asc')
            ->get();

        $histories['latest'] = Market::onlyTrashed()
            ->where('exchenge_id', $market->exchenge_id)
            ->where('currency_id_from', $market->currency_id_from)
            ->where('currency_id_to', $market->currency_id_to)
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('markets.show', compact('market', 'histories', 'orders', 'price'));
    }
}
