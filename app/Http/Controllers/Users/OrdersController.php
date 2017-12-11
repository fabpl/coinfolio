<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\Order;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
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
     * @param Request $request
     * @param int $market_id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, $market_id)
    {
        $market = Market::findOrFail($market_id);

        // Price
        $price = Price::first();

        $order = new Order();
        $order->market_id = $market->id;
        $order->price_id = $price->id;
        $order->user_id = Auth::user()->id;
        $order->quantity = $request->get('quantity');
        $order->last = $request->get('last');
        $order->save();

        return redirect(route('markets.show', array('market' => $market->id)));
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($market_id, $order_id)
    {
        $order = Order::where('user_id', Auth::user()->id)
            ->where('market_id', $market_id)
            ->findOrFail($order_id);

        $order->delete();

        return redirect(route('markets.show', array('market' => $order->market_id)));
    }
}
