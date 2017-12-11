<?php

namespace App\Http\Controllers;

use App\Models\Exchenge\Bittrex;
use App\Models\Price;
use App\Models\Price\Coindesk;
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
        $followings = $user->markets()->with(['exchenge', 'currency_from', 'currency_to'])->get();
        foreach ($followings as $k => $market) {
            $followings[$k] = $market->synchronize();
        }

        // Markets
        $markets = Market::with(['exchenge', 'currency_from', 'currency_to'])->get();

        return view('home', compact('markets', 'followings', 'price'));
    }
}
