@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="input__email">Email address</label>
                    <input type="email" class="form-control" id="input__email" name="email" placeholder="Enter email" required value="{{ old('email') }}">
                    @if ($errors->has('email'))
                        <small class="form-text text-muted">{{ $errors->first('email') }}</small>
                    @endif
                </div>

                <div class="form-group">
                    <label for="input__password">Password</label>
                    <input type="password" class="form-control" id="input__password" name="password" placeholder="Password" required>
                </div>

                <div class="form-check">
                    <label class="form-check-label">
                        <input {{ old('remember') ? 'checked' : '' }} type="checkbox" class="form-check-input" name="remember">
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>

                <a class="btn btn-link" href="{{ route('password.request') }}">
                    Forgot Your Password?
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
