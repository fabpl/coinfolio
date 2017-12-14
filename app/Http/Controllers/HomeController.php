<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Price
        $price = Price::first()->synchronize();

        // Followings
        $followings = $user->markets()->with(['exchange', 'currency_from', 'currency_to'])->get();
        foreach ($followings as $k => $market) {
            $followings[$k] = $market->synchronize();
        }

        // Markets
        $markets = Market::with(['exchange', 'currency_from', 'currency_to'])->get();

        // Orders
        $orders = $user->orders()->with(['market', 'price'])->get();

        return view('home', compact('followings', 'markets', 'orders', 'price'));
    }
}
