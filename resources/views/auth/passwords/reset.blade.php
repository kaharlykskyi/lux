@extends('layouts.app')

@section('content')

    <div id="content">
        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => 'Обновление пароля']
            ]
        ])

        @endcomponent

        <section class="login-sec padding-top-30 padding-bottom-100">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-offset-2 col-sm-8">
                        <h5 class="text-center margin-bottom-20">{{ __('Обновить пароль') }}</h5>

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <ul class="row">
                                <li class="col-sm-12">
                                    <label>{{__('E-mail адрес')}}
                                        <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" required autofocus>
                                    </label>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </li>
                                <li class="col-sm-12">
                                    <label>{{__('Пароль')}}
                                        <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                    @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </label>
                                </li>
                                <li class="col-sm-12">
                                    <label>{{__('Подтвердите пароль')}}
                                        <input type="password" class="form-control" name="password_confirmation" required>
                                    </label>
                                </li>
                                <li class="col-sm-12 text-left">
                                    <button type="submit" class="btn-round">{{__('Отправить')}}</button>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
