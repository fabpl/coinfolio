@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="input__name">Name</label>
                    <input type="text" class="form-control" id="input__name" name="name" placeholder="Enter name" required value="{{ old('name') }}">
                    @if ($errors->has('name'))
                        <small class="form-text text-muted">{{ $errors->first('name') }}</small>
                    @endif
                </div>

                <div class="form-group">
                    <label for="input__email">Email address</label>
                    <input type="email" class="form-control" id="input__email" name="email" placeholder="Enter email" required value="{{ old('email') }}">
                    @if ($errors->has('email'))
                        <small class="form-text text-muted">{{ $errors->first('email') }}</small>
                    @endif
                </div>

                <div class="form-group">
                    <label for="input__password">Password</label>
                    <input type="password" class="form-control" id="input__password" name="password" placeholder="Enter password" required>
                    @if ($errors->has('password'))
                        <small class="form-text text-muted">{{ $errors->first('password') }}</small>
                    @endif
                </div>

                <div class="form-group">
                    <label for="input__password_confirmation">Confirm password</label>
                    <input type="password" class="form-control" id="input__password_confirmation" name="password_confirmation" placeholder="Enter password confirmation" required>
                    @if ($errors->has('password_confirmation'))
                        <small class="form-text text-muted">{{ $errors->first('password_confirmation') }}</small>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection
