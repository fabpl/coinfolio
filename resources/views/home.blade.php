@extends('layouts.app')

@section('content')
    <div class="container">
        <form method="POST" action="{{route('profil.markets.store')}}">
            <div class="row">
                <div class="col-sm-9">
                    <div class="form-group">
                        <select class="form-control select2" name="market_id">
                            <option selected value="">Add market:</option>
                            @foreach($markets as $market)
                                <option value="{{$market->id}}">
                                    [{{$market->exchange->name}}]
                                    {{$market->currency_from->name}} ({{$market->currency_from->code}})
                                    - {{$market->currency_to->name}} ({{$market->currency_to->code}})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    {{csrf_field()}}
                    <button type="submit" class="btn btn-primary btn-block">Follow</button>
                </div>
            </div>
        </form>
    </div>

    <div class="container">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Exchange</th>
                <th>Market</th>
                <th>Last</th>
                <th>24h Change</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($followings as $market)
                <tr>
                    <td>{{$market->exchange->name}}</td>
                    <td>
                        <a href="{{route('markets.show', ['market' => $market->id])}}">
                            [{{$market->currency_from->code}}-{{$market->currency_to->code}}
                            ] {{$market->currency_from->name}}-{{$market->currency_to->name}}
                        </a>
                    </td>
                    <td>
                        @if($market->isFromBitcoin($market->currency_from->code))
                            <span data-bitcoin="Ƀ {{number_format($market->last, 8, ',', ' ')}}"
                                  data-usd="$ {{number_format($market->last * $price->usd, 8, ',', ' ')}}"
                                  data-eur="€ {{number_format($market->last * $price->eur, 8, ',', ' ')}}">
                            Ƀ {{number_format($market->last, 8, ',', ' ')}}
                        </span>
                        @else
                            {{$market->currency_from->symbol}} {{number_format($market->last, 8, ',', ' ')}}
                        @endif
                    </td>
                    <td>
                        <span class="@if($market->change>0) text-success @else text-danger @endif">
                            % {{number_format($market->change, 2, ',', ' ')}}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('profil.markets.delete', ['market' => $market->id]) }}"
                           onclick="event.preventDefault(); document.getElementById('form__delete_{{$market->id}}').submit();">
                            <i class="fa fa-trash-o"></i>
                        </a>
                        <form id="form__delete_{{$market->id}}"
                              action="{{ route('profil.markets.delete', ['market' => $market->id]) }}" method="POST"
                              style="display: none;">
                            <input type="hidden" name="market_id" value="{{$market->id}}">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No markets</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
