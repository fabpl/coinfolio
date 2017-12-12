@extends('layouts.app')

@section('content')
    <div class="container mb-4">
        <h2>[{{$market->currency_from->code}}-{{$market->currency_to->code}}] {{$market->currency_from->name}}
            - {{$market->currency_to->name}}</h2>
        <hr>
    </div>

    <div class="container mb-4">
        <h3>24hr Market data ({{$market->created_at}})</h3>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">High</h4>
                        <p class="card-text">
                            @if($market->isFromBitcoin($market->currency_from->code))
                                <span data-bitcoin="Ƀ {{number_format($market->high, 8, ',', ' ')}}"
                                      data-usd="$ {{number_format($market->high * $price->usd, 8, ',', ' ')}}"
                                      data-eur="€ {{number_format($market->high * $price->eur, 8, ',', ' ')}}">
                                    Ƀ {{number_format($market->high, 8, ',', ' ')}}
                                </span>
                            @else
                                {{$market->currency_from->symbol}} {{number_format($market->high, 8, ',', ' ')}}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Bid</h4>
                        <p class="card-text">
                            @if($market->isFromBitcoin($market->currency_from->code))
                                <span data-bitcoin="Ƀ {{number_format($market->bid, 8, ',', ' ')}}"
                                      data-usd="$ {{number_format($market->bid * $price->usd, 8, ',', ' ')}}"
                                      data-eur="€ {{number_format($market->bid * $price->eur, 8, ',', ' ')}}">
                                    Ƀ {{number_format($market->bid, 8, ',', ' ')}}
                                </span>
                            @else
                                {{$market->currency_from->symbol}} {{number_format($market->bid, 8, ',', ' ')}}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Volume</h4>
                        <p class="card-text">
                            @if($market->isFromBitcoin($market->currency_from->code))
                                <span data-bitcoin="Ƀ {{number_format($market->volume, 8, ',', ' ')}}"
                                      data-usd="$ {{number_format($market->volume * $price->usd, 8, ',', ' ')}}"
                                      data-eur="€ {{number_format($market->volume * $price->eur, 8, ',', ' ')}}">
                                Ƀ {{number_format($market->volume, 8, ',', ' ')}}
                            </span>
                            @else
                                {{$market->currency_from->symbol}} {{number_format($market->volume, 8, ',', ' ')}}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Low</h4>
                        <p class="card-text">
                            @if($market->isFromBitcoin($market->currency_from->code))
                                <span data-bitcoin="Ƀ {{number_format($market->low, 8, ',', ' ')}}"
                                      data-usd="$ {{number_format($market->low * $price->usd, 8, ',', ' ')}}"
                                      data-eur="€ {{number_format($market->low * $price->eur, 8, ',', ' ')}}">
                                Ƀ {{number_format($market->low, 8, ',', ' ')}}
                            </span>
                            @else
                                {{$market->currency_from->symbol}} {{number_format($market->low, 8, ',', ' ')}}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Ask</h4>
                        <p class="card-text">
                            @if($market->isFromBitcoin($market->currency_from->code))
                                <span data-bitcoin="Ƀ {{number_format($market->ask, 8, ',', ' ')}}"
                                      data-usd="$ {{number_format($market->ask * $price->usd, 8, ',', ' ')}}"
                                      data-eur="€ {{number_format($market->ask * $price->eur, 8, ',', ' ')}}">
                                Ƀ {{number_format($market->ask, 8, ',', ' ')}}
                            </span>
                            @else
                                {{$market->currency_from->symbol}} {{number_format($market->ask, 8, ',', ' ')}}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Last</h4>
                        <p class="card-text">
                            @if($market->isFromBitcoin($market->currency_from->code))
                                <span data-bitcoin="Ƀ {{number_format($market->last, 8, ',', ' ')}}"
                                      data-usd="$ {{number_format($market->last * $price->usd, 8, ',', ' ')}}"
                                      data-eur="€ {{number_format($market->last * $price->eur, 8, ',', ' ')}}">
                                Ƀ {{number_format($market->bid, 8, ',', ' ')}}
                            </span>
                            @else
                                {{$market->currency_from->symbol}} {{number_format($market->bid, 8, ',', ' ')}}
                            @endif
                            <span class="float-right badge @if($market->change>0) badge-success @else badge-danger @endif">
                                % {{number_format($market->change, 2, ',', ' ')}}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h3>Orders</h3>

        <form method="POST" action="{{route('profil.markets.orders.store', array('market_id' => $market->id))}}">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <input type="text" class="form-control" id="input__quantity" name="quantity"
                               placeholder="Enter quantity" required value="{{ old('quantity', 1) }}">
                        @if ($errors->has('quantity'))
                            <small class="form-text text-muted">{{ $errors->first('quantity') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <input type="text" class="form-control" id="input__last" name="last" placeholder="Enter last"
                               required value="{{ old('last', $market->last) }}">
                        @if ($errors->has('last'))
                            <small class="form-text text-muted">{{ $errors->first('last') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <input type="text" class="form-control" id="input__total" disabled
                               value="{{ old('last', $market->last) }}">
                    </div>
                </div>
                <div class="col-sm-3">
                    {{csrf_field()}}
                    <button type="submit" class="btn btn-primary btn-block">Add</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
            <tr>
                <td>Date</td>
                <td>Quantity</td>
                <td>Last</td>
                <td>Total</td>
                <td>Benefit</td>
                <td></td>
            </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{$order->created_at}}</td>
                    <td>{{number_format($order->quantity, 8, ',', ' ')}}</td>
                    <td>
                        @if($market->isFromBitcoin($market->currency_from->code))
                            <span data-bitcoin="Ƀ {{number_format($order->last, 8, ',', ' ')}}"
                                  data-usd="$ {{number_format($order->last * $order->price->usd, 8, ',', ' ')}}"
                                  data-eur="€ {{number_format($order->last * $order->price->eur, 8, ',', ' ')}}">
                            Ƀ {{number_format($order->last, 8, ',', ' ')}}
                        </span>
                        @else
                            {{$market->currency_from->symbol}} {{number_format($order->last, 8, ',', ' ')}}
                        @endif
                    </td>
                    <td>{{number_format($order->last * $order->quantity, 8, ',', ' ')}}</td>
                    <td>
                        @if($market->isFromBitcoin($market->currency_from->code))
                            <span data-bitcoin="Ƀ {{number_format(($market->last * $order->quantity) - ($order->last * $order->quantity), 8, ',', ' ')}}"
                                  data-usd="$ {{number_format((($market->last * $order->quantity) - ($order->last * $order->quantity)) * $order->price->usd, 8, ',', ' ')}}"
                                  data-eur="€ {{number_format((($market->last * $order->quantity) - ($order->last * $order->quantity)) * $order->price->eur, 8, ',', ' ')}}">
                            Ƀ {{number_format(($market->last * $order->quantity) - ($order->last * $order->quantity), 8, ',', ' ')}}
                        </span>
                        @else
                            {{$market->currency_from->symbol}} {{number_format(($market->last * $order->quantity) - ($order->last * $order->quantity), 8, ',', ' ')}}
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('profil.markets.orders.delete', ['market' => $market->id, 'order' => $order->id]) }}"
                           onclick="event.preventDefault(); document.getElementById('form__delete_{{$order->id}}').submit();">
                            <i class="fa fa-trash-o"></i>
                        </a>
                        <form id="form__delete_{{$order->id}}"
                              action="{{ route('profil.markets.orders.delete', ['market' => $market->id, 'order' => $order->id]) }}"
                              method="POST"
                              style="display: none;">
                            <input type="hidden" name="order_id" value="{{$order->id}}">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No orders</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="container">
        <h3>History</h3>

        <canvas class="mb-4" id="canvas__histories" width="undefined" height="undefined"></canvas>

        <table class="table table-bordered mb-4">
            <thead>
            <tr>
                <th>Date</th>
                <th>Hight</th>
                <th>Low</th>
                <th>Bid</th>
                <th>Ask</th>
                <th>Volume</th>
                <th>Last</th>
                <th>24h Change</th>
            </tr>
            </thead>
            <tbody>
            @forelse($histories['latest'] as $history)
                <tr>
                    <td>{{$history->created_at}}</td>
                    <td>{{$market->currency_from->symbol}} {{number_format($history->high, 8, ',', ' ')}}</td>
                    <td>{{$market->currency_from->symbol}} {{number_format($history->low, 8, ',', ' ')}}</td>
                    <td>{{$market->currency_from->symbol}} {{number_format($history->bid, 8, ',', ' ')}}</td>
                    <td>{{$market->currency_from->symbol}} {{number_format($history->ask, 8, ',', ' ')}}</td>
                    <td>{{$market->currency_from->symbol}} {{number_format($history->volume, 8, ',', ' ')}}</td>
                    <td>{{$market->currency_from->symbol}} {{number_format($history->last, 8, ',', ' ')}}</td>
                    <td>
                        <span class="@if($history->change>0) text-success @else text-danger @endif">
                            % {{number_format($history->change, 2, ',', ' ')}}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No histories</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{$histories['latest']->links("pagination::bootstrap-4")}}
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/vendor/chartjs.js') }}"></script>
    <script type="text/javascript">
        jQuery(function ($) {
            $('#input__quantity, #input__last').on('change', function () {
                $('#input__total').val($('#input__quantity').val() * $('#input__last').val());
            });

            @if(count($histories['all'])>0)
            new Chart(document.getElementById("canvas__histories"), {
                "type": "line",
                "data": {
                    "labels": [
                        @foreach($histories['all'] as $history)
                            "{{$history->created_at}}"@if (!$loop->last),@endif
                        @endforeach
                    ],
                    "datasets": [
                        {
                            "label": "Ƀ Last",
                            "data": [
                                @foreach($histories['all'] as $history)
                                {{$history->last}}@if (!$loop->last),@endif
                                @endforeach
                            ],
                            "fill": false,
                            "borderColor": "rgb(75, 192, 192)",
                            "lineTension": 0.1
                        }
                    ]
                },
                "options": {}
            });
            @endif
        });
    </script>
@endsection
