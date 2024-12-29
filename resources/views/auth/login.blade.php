@extends('layouts.admin.auth')

@section('content')
    <form method="POST" action="{{ route('login') }}"> 
    @csrf 
        <div class="form-group mb-3">
            <label class="form-label">Enter Email address</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus> @error('email') <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span> @enderror
        </div>
        <div class="form-group mb-3">
            <label class="form-label">Enter Password</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"> @error('password') <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span> @enderror
        </div>
        <div class="form-group mb-4">
            <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">
                {{ __('Remember Me') }}
            </label>
            </div>
        </div>
        <div class="d-grid mb-4">
            <button class="btn btn-primary btn-block mt-2"> Log In </button>
        </div>
    </form>
@endsection
