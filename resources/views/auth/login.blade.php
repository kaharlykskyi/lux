@extends('layouts.app')

@section('content')

    <div id="content">
        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => 'Войти']
            ]
        ])
        @endcomponent

        <section class="login-sec padding-top-30 padding-bottom-100">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-offset-2 col-sm-8">
                        <h5 class="text-center margin-bottom-20">{{ __('Войти в свой акаунт') }}</h5>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <ul class="row">
                                <li class="col-sm-12">
                                    <label>{{__('E-mail')}}
                                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                                    </label>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </li>
                                <li class="col-sm-12">
                                    <label>{{__('Пароль')}}
                                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </label>
                                </li>
                                <li class="col-sm-6">
                                    <div class="checkbox checkbox-primary">
                                        <input id="cate1" class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label for="cate1"> {{__('Запомнить меня')}} </label>
                                    </div>
                                </li>
                                <li class="col-sm-6"> <a href="{{route('password.request')}}" class="forget">{{__('Забыли пароль?')}}</a> </li>
                                <li class="col-sm-12 text-left">
                                    <button type="submit" class="btn-round">{{__('Войти')}}</button>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
