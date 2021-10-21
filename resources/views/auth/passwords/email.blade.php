@extends('backpack::auth.layout')

<!-- Main Content -->
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
                    <div class="box-title login-title">Customer {{ __('Reset Password') }}</div>
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
                    <form method="POST" action="{{ route('password.email') }}" aria-label="{{ __('Reset Password') }}">
                        @csrf

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label">{{ __('E-Mail Address') }}</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-danger">
                                {{ __('Send Password Reset Link') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

