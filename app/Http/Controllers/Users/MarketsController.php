<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Market;
use Illuminate\Http\Request;
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
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $market = Market::findOrFail($request->get('market_id'));

        $user = Auth::user();
        $user->markets()->detach($market->id);
        $user->markets()->attach($market->id);

        return redirect(route('home'));
    }

    /**
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        $market = Market::findOrFail($id);

        $user = Auth::user();
        $user->markets()->detach($market->id);

        return redirect(route('home'));
    }
}
