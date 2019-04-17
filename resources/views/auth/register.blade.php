@extends('layouts.app')

@section('content')

    <div id="content">
        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => 'Регистрация']
            ]
        ])

        @endcomponent

        <section class="login-sec padding-top-30 padding-bottom-100">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-offset-2 col-sm-8">
                        <h5 class="text-center margin-bottom-20">{{ __('Регистрация') }}</h5>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <ul class="row">
                                <li class="col-sm-12">
                                    <label>{{ __('Имя') }}
                                        <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                                    </label>
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </li>
                                <li class="col-sm-12">
                                    <label>{{__('Фамилия')}}
                                        <input type="text" class="form-control {{ $errors->has('sername') ? ' is-invalid' : '' }}" name="sername" value="{{ old('sername') }}" required>
                                    </label>
                                    @if ($errors->has('sername'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('sername') }}</strong>
                                        </span>
                                    @endif
                                </li>
                                <li class="col-sm-12">
                                    <label>{{__('Отчество')}}
                                        <input type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" required>
                                    </label>
                                    @if ($errors->has('last_name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @endif
                                </li>
                                <li class="col-sm-12">
                                    <label>{{__('Адрес електронной почты')}}
                                        <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                                    </label>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </li>
                                <li class="col-sm-12">
                                    <label>{{__('Телефон')}}
                                        <input type="tel" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone') }}" required>
                                    </label>
                                    @if ($errors->has('phone'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </li>
                                <li class="col-sm-12">
                                    <label class="relative country">{{__('Страна')}}
                                        <input id="country" oninput="getCountry($(this))" type="text" class="phone_mask form-control{{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" value="{{ old('country') }}" required autocomplete="off">
                                        <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                                    </label>
                                    @if ($errors->has('country'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                    @endif
                                </li>
                                <li class="col-sm-12">
                                    <label class="relative city">{{__('Город')}}
                                        <input id="city" oninput="getCity($(this))" type="text" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ old('city') }}" required autocomplete="off">
                                        <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                                    </label>
                                    @if ($errors->has('city'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('city') }}</strong>
                                        </span>
                                    @endif
                                </li>
                                <li class="col-sm-12">
                                    <label>{{__('Тип клиента')}}
                                        <select class="form-control" name="role" required>
                                            @isset($roles)
                                                @foreach($roles as $role)
                                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </label>
                                    @if ($errors->has('role'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('role') }}</strong>
                                        </span>
                                    @endif
                                </li>
                                <li class="col-sm-12">
                                    <label>{{__('Пароль')}}
                                        <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                    </label>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </li>
                                <li class="col-sm-12">
                                    <label>{{__('Подтвердите пароль')}}
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                    </label>
                                </li>
                                <li class="col-sm-12 text-left">
                                    <button type="submit" class="btn-round">{{__('Регистрация')}}</button>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
