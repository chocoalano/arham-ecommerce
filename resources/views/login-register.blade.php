@extends('layouts.app')

@section('title','Login / Register')

@section('content')
    @livewire('breadscrumb')

    <div class="page-section mb-80">
        <div class="container">
            <div class="row">
                {{-- LOGIN --}}
                <div class="col-sm-12 col-md-12 col-xs-12 col-lg-6 mb-30">
                    <form method="POST" action="{{ route('login-register.login') }}" novalidate>
                        @csrf
                        <input type="hidden" name="redirect" value="{{ request('redirect', url()->previous() !== url()->current() ? url()->previous() : route('home')) }}">

                        <div class="login-form">
                            <h4 class="login-title">Login</h4>

                            @if(session('error'))
                                <div class="alert alert-danger mb-3">{{ session('error') }}</div>
                            @endif

                            <div class="row">
                                <div class="col-md-12 col-12 mb-20">
                                    <label for="login_email">Email Address*</label>
                                    <input id="login_email" class="mb-0 form-control @error('email') is-invalid @enderror"
                                           type="email" name="email" placeholder="Email Address"
                                           value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 mb-20">
                                    <label for="login_password">Password</label>
                                    <input id="login_password" class="mb-0 form-control @error('password') is-invalid @enderror"
                                           type="password" name="password" placeholder="Password" required autocomplete="current-password">
                                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-8">
                                    <div class="check-box d-inline-block ml-0 ml-md-2 mt-10">
                                        <input type="checkbox" id="remember_me" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                                        <label for="remember_me">Remember me</label>
                                    </div>
                                </div>

                                <div class="col-md-4 mt-10 mb-20 text-start text-md-end">
                                    <a href="#">Forgotten password?</a>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="register-button mt-0">Login</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- REGISTER --}}
                <div class="col-sm-12 col-md-12 col-lg-6 col-xs-12">
                    <form method="POST" action="{{ route('login-register.register') }}" novalidate>
                        @csrf
                        <input type="hidden" name="redirect" value="{{ request('redirect', route('home')) }}">

                        <div class="login-form">
                            <h4 class="login-title">Register</h4>

                            @if(session('success'))
                                <div class="alert alert-success mb-3">{{ session('success') }}</div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger mb-3">{{ session('error') }}</div>
                            @endif

                            <div class="row">
                                <div class="col-md-6 col-12 mb-20">
                                    <label for="first_name">First Name</label>
                                    <input id="first_name" class="mb-0 form-control @error('first_name') is-invalid @enderror"
                                           type="text" name="first_name" placeholder="First Name" value="{{ old('first_name') }}" required>
                                    @error('first_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 col-12 mb-20">
                                    <label for="last_name">Last Name</label>
                                    <input id="last_name" class="mb-0 form-control @error('last_name') is-invalid @enderror"
                                           type="text" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}">
                                    @error('last_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-12 mb-20">
                                    <label for="reg_email">Email Address*</label>
                                    <input id="reg_email" class="mb-0 form-control @error('email') is-invalid @enderror"
                                           type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required autocomplete="email">
                                    @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-20">
                                    <label for="reg_password">Password</label>
                                    <input id="reg_password" class="mb-0 form-control @error('password') is-invalid @enderror"
                                           type="password" name="password" placeholder="Password" required autocomplete="new-password">
                                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-20">
                                    <label for="reg_password_confirmation">Confirm Password</label>
                                    <input id="reg_password_confirmation" class="mb-0 form-control"
                                           type="password" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="register-button mt-0">Register</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
