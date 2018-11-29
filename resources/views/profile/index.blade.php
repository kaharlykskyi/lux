@extends('layouts.app')

@section('content')
    <!-- Content -->
    <div id="content" class="profile-wrapper">

        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => 'Личный кабинет']
            ]
        ])
        @endcomponent

        <div class="container margin-top-20">
            @if(!isset(Auth::user()->email_verified_at))
                <div class="row padding-10">
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                            <strong>{{ __('Електронный адрес не подтверждён! ') }}</strong>
                            {{ __('Если вы не получили письмо') }},
                            <a class="text-danger" href="{{ route('verification.resend') }}">
                                <em>{{ __('нажмите здесь, чтобы запросить снова') }}</em>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            @if(session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="row" id="tabs">
                <div class="col-md-3">
                    <div class="panel panel-primary">
                        <div class="panel-collapse">
                            <ul class="list-group list-unstyled">
                                <li id="nav-tabs-1" data-id-href="tabs-1" class="list-group-item">{{__('Личные даннные')}}</li>
                                <li id="nav-tabs-3" data-id-href="tabs-3" class="list-group-item">{{__('Информация о доставке')}}</li>
                                <li id="nav-tabs-2" data-id-href="tabs-2" class="list-group-item">{{__('Заказы')}}</li>
                                <li id="nav-tabs-4" data-id-href="tabs-4" class="list-group-item">{{__('Мои автомобили')}}</li>
                                <li id="nav-tabs-5" data-id-href="tabs-5" class="list-group-item">{{__('Смена пароля')}}</li>
                                <li id="nav-tabs-6" data-id-href="tabs-6" class="list-group-item">{{__('Мои возвраты')}}</li>
                                <li id="nav-tabs-7" data-id-href="tabs-7" class="list-group-item">{{__('Баланс')}}</li>
                                <li id="nav-tabs-8" data-id-href="tabs-8" class="list-group-item">{{__('Взаиморасчеты')}}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 tab-item active">
                    <div class="panel panel-primary">
                        <div class="panel-heading">{{__('Личный кабинет')}}</div>
                        <div class="panel-body panel-profile">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="profile-item profile-item-user">
                                        <a class="link-prof-item" data-id-href="tabs-1" href="#">
                                            <span>{{__('Личные данные')}}</span>
                                        </a>
                                        <small>{{__('В этом разделе вы можете изменить свои личные данные.')}}</small>
                                    </div>
                                </div>
                                <div class="col-md-6 padding-md-30">
                                    <div class="profile-item profile-item-delivery">
                                        <a id="tab-3" class="link-prof-item" data-id-href="tabs-3" href="#">
                                            <span>{{__('Информация о доставке')}}</span>
                                        </a>
                                        <small>{{__('В этом разделе вы можете изменить данные доставки.')}}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="profile-item profile-item-orders">
                                        <a class="link-prof-item" data-id-href="tabs-2" href="#">
                                            <span>{{__('Заказы')}}</span>
                                        </a>
                                        <small>{{__('Информация о всех ваших заказах: номера, даты, состав заказов и их статусы.')}}</small>
                                    </div>
                                </div>
                                <div class="col-md-6 padding-md-30">
                                    <div class="profile-item profile-item-car">
                                        <a class="link-prof-item" data-id-href="tabs-4" href="#">
                                            <span>{{__('Мои автомобили')}}</span>
                                        </a>
                                        <small>{{__('Здесь можно добавить свой автомобиль.')}}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="profile-item profile-item-pwd">
                                        <a class="link-prof-item" data-id-href="tabs-5" href="#">
                                            <span>{{__('Смена пароля')}}</span>
                                        </a>
                                        <small>{{__('Здесь вы можете сменить свои данные для доступа в личный кабинет.')}}</small>
                                    </div>
                                </div>
                                <div class="col-md-6 padding-md-30">
                                    <div class="profile-item profile-item-retweet">
                                        <a class="link-prof-item" data-id-href="tabs-6" href="#">
                                            <span>{{__('Мои возвраты')}}</span>
                                        </a>
                                        <small>{{__('Здесь вы можете просмотреть заказы которые были поданны на возврат.')}}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="profile-item profile-item-money">
                                        <a class="link-prof-item" data-id-href="tabs-7" href="#">
                                            <span>{{__('Баланс')}}</span>
                                        </a>
                                        <small>{{__('Здесь вы можете просмотреть баланс и историю пополнений.')}}</small>
                                    </div>
                                </div>
                                <div class="col-md-6 padding-md-30">
                                    <div class="profile-item profile-item-chart">
                                        <a class="link-prof-item" data-id-href="tabs-8" href="#">
                                            <span>{{__('Взаиморасчеты')}}</span>
                                        </a>
                                        <small>{{__('Здесь вы можете просмотреть все действи с вашим счётом.')}}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 tab-item" id="tabs-1">
                    <div class="panel panel-primary">
                        <div class="panel-heading">{{__('Личные данные')}}</div>
                        <div class="panel-body panel-profile">
                            <form type="POST" class="ajax-form ajax2" action="{{route('change_user_info')}}">
                                @csrf
                                <ul class="row login-sec">
                                    <li class="col-sm-12">
                                        <label>{{ __('Имя') }}
                                            <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}" required autofocus>
                                        </label>
                                    </li>
                                    <li class="col-sm-12">
                                        <label>{{__('Фамилия')}}
                                            <input type="text" class="form-control" name="sername" value="{{ Auth::user()->sername }}" required>
                                        </label>
                                    </li>
                                    <li class="col-sm-12">
                                        <label>{{__('Отчество')}}
                                            <input type="text" class="form-control" name="last_name" value="{{ Auth::user()->last_name }}" required>
                                        </label>
                                    </li>
                                    <li class="col-sm-12">
                                        <label>{{__('Адрес електронной почты')}}
                                            @if(isset(Auth::user()->email_verified_at))
                                                <i class="fa fa-check text-success" aria-hidden="true" title="Подтверждён"></i>
                                            @else
                                                <a class="text-danger" href="{{ route('verification.resend') }}">
                                                    <i class="fa fa-times" aria-hidden="true" title="Не подтверждён"></i>
                                                </a>
                                            @endif
                                            <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" required>
                                        </label>
                                    </li>
                                    <li class="col-sm-12">
                                        <label>{{__('Телефон')}}
                                            <input type="tel" class="form-control" name="phone" value="{{ Auth::user()->phone }}" required>
                                        </label>
                                    </li>
                                    <li class="col-sm-12">
                                        <label class="relative country">{{__('Страна')}}
                                            <input id="country" oninput="getCountry($(this))" type="text" class="form-control" name="country" value="{{ Auth::user()->country }}" required autocomplete="off">
                                            <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                                        </label>
                                    </li>
                                    <li class="col-sm-12">
                                        <label class="relative city">{{__('Город')}}
                                            <input id="city" oninput="getCity($(this),'#country')" type="text" class="form-control" name="city" value="{{ Auth::user()->city }}" required autocomplete="off">
                                            <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                                        </label>
                                    </li>
                                    <li class="col-sm-12">
                                        <label>{{__('Тип клиента')}}
                                            <select class="form-control" name="role" required>
                                                @isset($roles)
                                                    @foreach($roles as $role)
                                                        <option @if($role->id == Auth::user()->role) selected @endif value="{{$role->id}}">{{$role->name}}</option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                        </label>
                                    </li>
                                    <li class="col-sm-12 text-left">
                                        <button type="submit" class="btn-round">{{__('Сохранить')}}</button>
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 tab-item" id="tabs-2">
                    <div class="panel panel-primary">
                        <div class="panel-heading">{{__('Мои заказы')}}</div>
                        <div class="panel-body panel-profile">
                            <div class="row login-sec">
                                @forelse($orders as $order)

                                @empty
                                    <div class="alert alert-info margin-15" role="alert">
                                        Похоже вы еще не делали заказы, <strong>начните прямо сейчас</strong>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 tab-item" id="tabs-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">{{__('Информация о доставке')}}</div>
                        <div class="panel-body panel-profile">
                            <form type="POST" action="{{route('change_delivery_info')}}" class="ajax-form ajax2">
                                @csrf
                                <ul class="row login-sec">
                                    <li class="col-sm-12">
                                        <label class="relative country">{{__('Страна')}}
                                            <input id="delivery_country" oninput="getCountry($(this),'#delivery_country')" type="text" class="form-control" name="delivery_country" value="@isset($delivery_info){{ $delivery_info->delivery_country }}@endisset" autocomplete="off">
                                            <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                                        </label>
                                    </li>
                                    <li class="col-sm-12">
                                        <label class="relative city">{{__('Город')}}
                                            <input id="delivery_city" oninput="getCity($(this),'#delivery_country')" type="text" class="form-control" name="delivery_city" value="@isset($delivery_info){{ $delivery_info->delivery_city }}@endisset" autocomplete="off">
                                            <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                                        </label>
                                    </li>

                                    <li class="col-sm-12">
                                        <label>{{ __('Улица') }}
                                            <input type="text" class="form-control" name="street" value="@isset($delivery_info){{ $delivery_info->street }}@endisset">
                                        </label>
                                    </li>

                                    <li class="col-sm-12">
                                        <label>{{ __('Дом') }}
                                            <input type="text" class="form-control" name="house" value="@isset($delivery_info){{ $delivery_info->house }}@endisset">
                                        </label>
                                    </li>

                                    <li class="col-sm-12">
                                        <label>{{ __('Контактный телефон') }}
                                            <input type="text" class="form-control" name="phone" value="@isset($delivery_info){{ $delivery_info->phone }}@endisset">
                                        </label>
                                    </li>

                                    <li class="col-sm-12">
                                        <label>{{__('Служба доставки')}}
                                            <select class="form-control" name="delivery_service" id="delivery_service">
                                                <option @isset($delivery_info) @if($delivery_info->delivery_service === 'novaposhta') selected @endif @endisset value="{{__('novaposhta')}}">{{__('Новая Почта')}}</option>
                                            </select>
                                        </label>
                                    </li>

                                    <li class="col-sm-12 delivery-dep" style="display:none;">
                                        <label class="relative delivery-department">{{ __('Номер отделения') }}
                                            <input type="text" class="form-control" id="delivery_department" name="delivery_department" value="@isset($delivery_info){{ $delivery_info->delivery_department }}@endisset" autocomplete="off">
                                            <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                                        </label>
                                    </li>

                                    <li class="col-sm-12">
                                        <script src="{{asset('js/map.js')}}"></script>
                                        <div id="map" style="height: 200px;"></div>
                                        <script type="text/javascript" async defer
                                                src="https://maps.googleapis.com/maps/api/js?v=3&libraries=places&callback=initMap&key={{config('app.google_key')}}"></script>
                                    </li>

                                    <li class="col-sm-12 text-left">
                                        <button type="submit" class="btn-round">{{__('Сохранить')}}</button>
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 tab-item" id="tabs-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">{{__('Мои автомобили')}}</div>
                        <div class="panel-body panel-profile">
                            <div class="row login-sec">
                                <div class="col-sm-12 text-right">
                                    <button type="button" data-toggle="modal" data-target="#addCar" class="btn-round">{{__('Добавить автомобиль')}}</button>
                                </div>
                                <div class="col-sm-12">
                                    <div class="list-group padding-top-10" id="addedCars">
                                        @isset($user_cars)
                                            @foreach($user_cars as $user_car)
                                                <a href="#" class="list-group-item">
                                                    <p class="list-group-item-text">VIN код: {{$user_car->vin_code}}</p>
                                                    <p class="list-group-item-text">Марка: {{$user_car->mark}}</p>
                                                    <p class="list-group-item-text">Год выпуска: {{$user_car->year}}</p>
                                                    <p class="list-group-item-text">Модель: {{$user_car->model}}</p>
                                                    <p class="list-group-item-text">Обьем двигателя: {{$user_car->v_motor}}</p>
                                                    <p class="list-group-item-text">Тип двигателя: {{$user_car->type_motor}}</p>
                                                </a>
                                            @endforeach
                                        @endisset
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 tab-item" id="tabs-5">
                    <div class="panel panel-primary">
                        <div class="panel-heading">{{__('Смена пароля')}}</div>
                        <div class="panel-body panel-profile">
                            <form class="ajax-form ajax2" type="POST" action="{{route('change_password')}}">
                                @csrf
                                <ul class="row login-sec">
                                    <li class="col-sm-12">
                                        <label>{{ __('Новый пароль') }}
                                            <input type="password" class="form-control {{ $errors->has('new_password') ? ' is-invalid' : '' }}" name="new_password" value="{{ old('new_password') }}" required autofocus>
                                        </label>
                                    </li>
                                    <li class="col-sm-12 text-left">
                                        <button type="submit" class="btn-round">{{__('Сменить пароль')}}</button>
                                    </li>
                                </ul>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 tab-item" id="tabs-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">{{__('Мои возвраты')}}</div>
                        <div class="panel-body panel-profile">
                            <div class="alert alert-info margin-15" role="alert">
                                Возвратов нету
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 tab-item" id="tabs-7">
                    <div class="panel panel-primary">
                        <div class="panel-heading">{{__('Баланс')}}</div>
                        <div class="panel-body panel-profile">
                            <div class="col-sm-12">
                                <ul class="row login-sec">
                                    <li class="col-sm-6">
                                        <p class="h4">Баланс: <strong>0.00</strong> грн</p>
                                    </li>
                                    <li class="col-sm-6 text-right">
                                        <button type="button" class="btn-round">{{__('Пополнить баланс')}}</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-sm-12">
                                <table class="table">
                                    <caption>{{__('История пополнений')}}</caption>
                                    <thead>
                                        <tr>
                                            <th>Дата</th>
                                            <th>Сумма</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>4324</td>
                                            <td>23.06.18</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 tab-item" id="tabs-8">
                    <div class="panel panel-primary">
                        <div class="panel-heading">{{__('Взаиморасчеты')}}</div>
                        <div class="panel-body panel-profile">
                            <div class="row login-sec">
                                <div class="col-sm-12 table-responsive" style="overflow: visible;">
                                    <table class="table table-bordered table-hover" id="creaking_account">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Дата</th>
                                                <th>Документ</th>
                                                <th>Валюта</th>
                                                <th>Приход</th>
                                                <th>Расход</th>
                                                <th>Остаток</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="identification-wrapper">
                                                    <i class="fa fa-plus-square-o" style="cursor: pointer" aria-hidden="true"></i>
                                                    <div class="identification-info">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                <tr>
                                                                    <th>Дата</th>
                                                                    <th>Вид действия</th>
                                                                    <th>Идентификатор</th>
                                                                    <th>Описание</th>
                                                                    <th>Сумма</th>
                                                                    <th>НДС</th>
                                                                    <th>Дата</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr>
                                                                    <td>23.06.18</td>
                                                                    <td>Оплата</td>
                                                                    <td>Оплата</td>
                                                                    <td >Заказ SP32454623</td>
                                                                    <td>-2000</td>
                                                                    <td>00.00</td>
                                                                    <td>23.05.18</td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>23.06.18</td>
                                                <td>Расходная накладная №3245656</td>
                                                <td>USD</td>
                                                <td >354</td>
                                                <td></td>
                                                <td>0.00</td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @component('profile.component.add_car_model')@endcomponent

    <script>
        $(document).ready(function () {

            if ($('#delivery_service').val() === 'novaposhta' && $('#delivery_city').val().length > 0){
                $('.delivery-dep').show();
            }

            $('#delivery_service').change(function () {
                if ($(this).val() === 'novaposhta' && $('#delivery_city').val().length > 0){
                    $('.delivery-dep').show();
                } else {
                    $('.delivery-dep').hide();
                }
            });

            $('#delivery_city').on('input',function () {
                if ($('#delivery_service').val() === 'novaposhta' && $(this).val().length > 0){
                    $('.delivery-dep').show();
                } else {
                    $('.delivery-dep').hide();
                }
            });

            $(function($){
                $(document).mouseup(function (e){
                    const div = $(".identification-info");
                    if (!div.is(e.target)
                        && div.has(e.target).length === 0) {
                        div.hide();
                    }
                });
            });

            $('.identification-wrapper i').click(function (evt) {
                $('.identification-info').css({display: 'none'});
                if ($(this).is(evt.target)){
                    const y = evt.pageY - $('#creaking_account').offset().top;
                    $(this).siblings().css({
                        display: 'block',
                        top: y
                    });
                }
            });

            $('.list-group-item, a.link-prof-item').click(function (e) {
                e.preventDefault();
                $('.tab-item').removeClass('active');
                $('.list-group-item').removeClass('active');
                $(`#${$(this).attr('data-id-href')}`).addClass('active');
                $(`#nav-${$(this).attr('data-id-href')}`).addClass('active');
            });

            $('.ajax1').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data.errors !== undefined){
                            let errors_html =  ``;
                            for (let key in data.errors){
                                errors_html += `${data.errors[key][0]}\n`;
                            }
                            alert(errors_html);
                        } else {
                            $('#addedCars').append(`
                                <a href="#" class="list-group-item">
                                     <p class="list-group-item-text">VIN код: ${data.response.vin_code}</p>
                                     <p class="list-group-item-text">Марка: ${data.response.mark}</p>
                                     <p class="list-group-item-text">Год выпуска: ${data.response.year}</p>
                                     <p class="list-group-item-text">Модель: ${data.response.model}</p>
                                     <p class="list-group-item-text">Обьем двигателя: ${data.response.v_motor}</p>
                                     <p class="list-group-item-text">Тип двигателя: ${data.response.type_motor}</p>
                                </a>`);
                        }
                    }
                });
            });

            $('.ajax2').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data.errors !== undefined){
                            let errors_html =  ``;
                            for (let key in data.errors){
                                errors_html += `${data.errors[key][0]}\n`;
                            }
                            alert(errors_html);
                        } else {
                            alert(data.response)
                        }
                    }
                });
            });

            $('#nav-tabs-3, #tab-3').click(function () {
                if ($('#delivery_city').val().length > 0 && $('#delivery_department').val().length < 1){
                    getPlacePost('delivery_city');
                }
                if($('#delivery_department').val().length > 0) {
                    getPostOfice('delivery_city');
                }
            });

            $('#delivery_city').blur(function () {
                getPlacePost('delivery_city');
            });

            $('#delivery_department').blur(function () {
                if ($(this).val().length > 0){
                    getPostOfice('delivery_city');
                }
            });

            $('#delivery_department').on('input',function () {
                const flag = ($('#delivery_service').val() === 'novaposhta');
                if (flag && $(this).val().length > 0){
                    const city = $('#delivery_city').val();
                    $(this).autocomplete({
                        source: (request, response) => {
                            $('.delivery-department .loader').css({display: 'inline-block'});
                            $.ajax({
                                url: 'https://api.novaposhta.ua/v2.0/json/',
                                method: "POST",
                                data:JSON.stringify({
                                    "apiKey": "{{config('app.novaposhta_key')}}",
                                    "modelName": "Address",
                                    "calledMethod": "getWarehouses",
                                    "methodProperties": {
                                        "Language": "ru",
                                        "CityName": `${city}`,
                                        "FindByString": $(this).val()
                                    }
                                }),
                                success: (data) => {
                                    $('.delivery-department .loader').css({display: 'none'});
                                    response($.map(data.data, (item) => {
                                        console.log(data.data);
                                        return{
                                            value: item.DescriptionRu,
                                        }
                                    }));
                                }
                            });
                        },
                        minLength: 0
                    });
                }

            });

        });
    </script>

@endsection