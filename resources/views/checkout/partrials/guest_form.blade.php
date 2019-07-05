<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active">
        <a href="#new_user" aria-controls="home" role="tab" data-toggle="tab">
            {{__('Я новый покупатель')}}
        </a>
    </li>
    <li role="presentation">
        <a href="#old_user" aria-controls="profile" role="tab" data-toggle="tab">
            {{__('Я постоянный клиент')}}
        </a>
    </li>
</ul>

<!-- Tab panes -->
<div class="login-sec padding-top-30 tab-content">
    <div role="tabpanel" class="tab-pane active" id="new_user">
        <form type="POST" action="{{route('checkout.new_user')}}">
            <input type="hidden" name="order_id" value="{{isset($cart->id)?$cart->id:''}}">
            @csrf
            <ul class="row">
                <li class="col-sm-12">
                    <label>{{ __('ФИО') }}
                        <input type="text" class="form-control {{ $errors->has('fio') ? ' is-invalid' : '' }}" name="fio" value="{{ old('fio') }}" required autofocus>
                    </label>
                    @if ($errors->has('fio'))
                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('fio') }}</strong>
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
                        <input id="country" oninput="getCountry($(this))" type="text" class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" value="{{ old('country') }}" required autocomplete="off">
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
                        <input id="city" oninput="getCity($(this),'#country')" type="text" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ old('city') }}" required autocomplete="off">
                        <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                    </label>
                    @if ($errors->has('city'))
                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('city') }}</strong>
                                        </span>
                    @endif
                </li>
                <li class="col-sm-12">
                    <label>{{__('Способы оплаты')}}
                        <select name="pay_method" id="pay_method" class="form-control">
                            <option value="receipt" selected >{{__('При получении')}}</option>
                            <option value="online" disabled>{{__('Онлайн')}}</option>
                        </select>
                    </label>
                </li>
                <li class="col-sm-12">
                    <label>{{__('Доставка *')}}
                        <select id="delivery-service" name="delivery_service" class="form-control" required>
                            <option value="novaposhta" selected>{{__('Новая почта')}}</option>
                            <option value="samovivoz">{{__('Самовывоз')}}</option>
                        </select>
                    </label>
                </li>
                <li class="col-sm-12 delivery-dep" style="display:none;">
                    <label class="relative delivery-department">{{__('Отделение  *')}}
                        <input id="delivery_department" type="text" class="form-control{{ $errors->has('delivery_department') ? ' is-invalid' : '' }}" name="delivery_department" autocomplete="off">
                        <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                    </label>
                    @if ($errors->has('delivery_department'))
                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('delivery_department') }}</strong>
                                        </span>
                    @endif
                </li>
                <li class="col-sm-12 padding-bottom-10 padding-top-10">
                    <script src="{{asset('js/map.js')}}"></script>
                    <div id="map" style="height: 330px;"></div>
                    <script type="text/javascript" async defer
                            src="https://maps.googleapis.com/maps/api/js?v=3&libraries=places&callback=initMap&key={{config('app.google_key')}}"></script>
                </li>
                <li class="col-sm-12">
                    <label>{{__('Пароль')}}

                        <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                    </label>
                    @if ($errors->has('password *'))
                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                    @endif
                </li>
                <li class="col-sm-12">
                    <label>{{__('Подтвердите пароль *')}}
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </label>
                </li>
                <li class="col-sm-12 text-left">
                    <button type="submit" class="btn-round">{{__('Регистрация')}}</button>
                </li>
            </ul>
        </form>
    </div>
    <div role="tabpanel" class="tab-pane" id="old_user">
        <form method="POST" action="{{ route('checkout.old_user') }}">
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
