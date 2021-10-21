@extends('backpack::auth.layout')

@section('content')
    <div class="row">
        <div class="login-col">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="logo-admin">
                        <img src="{{ asset('images/logo.png') }}">
                    </div>
                    <div class="box-title">Login to start your session</div>
                </div>
                <div class="box-body">
                    <form method="POST" action="{{ route('backpack.auth.login') }}">
                        {!! csrf_field() !!}
                        <div class="form-group{{ $errors->has($username) ? ' has-error' : '' }}">
                            <label class="control-label">{{ config('backpack.base.authentication_column_name') }}</label>
                            <input type="text" class="form-control" name="{{ $username }}" value="{{ old($username) }}">
                            @if ($errors->has($username))
                                <span class="help-block">
                                    <strong>{{ $errors->first($username) }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="control-label">{{ trans('backpack::base.password') }}</label>
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
                                    <input type="checkbox" name="remember"> {{ trans('backpack::base.remember_me') }}
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                {{ trans('backpack::base.login') }}
                            </button>
                            @if (backpack_users_have_email())
                                <a class="btn btn-link" href="{{ route('backpack.auth.password.reset') }}">{{ trans('backpack::base.forgot_your_password') }}</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
