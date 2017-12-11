@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="jumbotron mt-4">
            <h1 class="display-3">{{ config('app.name', 'Laravel') }}</h1>
            <p class="lead">Altcoin Portfolio</p>
            <hr class="my-4">
            <p>Coinfolio offers cryptocurrency management. Get detailed price and market information for individual
                currencies all in one place.</p>
            <p class="lead">
                <a class="btn btn-primary btn-lg" href="{{route('register')}}" role="button">Register</a> - or <a
                        href="{{route('login')}}" role="button">Login</a>
            </p>
        </div>
    </div>
@endsection
