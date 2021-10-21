@extends('backpack::auth.layout')
@section('content')
    <div class="row">
        <div class="login-col">
            <div class="box box-default">
                <div class="box-header with-border">
                    @if(\File::exists(public_path('uploads/logo/' . $company->logo)))
                        <div class="logo-admin">
                            <img src="{{ asset('uploads/logo/' . $company->logo) }}" width="340px">
                        </div>
                    @endif
                    
                </div>
                <div class="box-body">
                    <div class="box-title login-title">Admin {{ __('Login') }}</div>
                    @if(session('status') && session('message'))
                        @if(session('status') == 'success')
                            <div class="alert alert-success">
                                <p>{{ session('message') }}</p>
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <p>{{ session('message') }}</p>
                            </div>
                        @endif
                    @endif
                    <form method="POST" action="{{ route('admin.auth.login') }}" aria-label="{{ __('Login') }}">
                        @csrf
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label">{{ __('E-Mail Address') }}</label>
                            <input type="text" class="form-control" name="email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="control-label">{{ __('Password') }}</label>
                            <input type="password" class="form-control" name="password">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> 
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-danger">
                                {{ __('Login') }}
                            </button>
                            <a class="btn btn-link" href="{{ route('admin.auth.show.password.reset') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
