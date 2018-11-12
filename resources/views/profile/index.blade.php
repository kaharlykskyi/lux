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
                            <strong>{{ __('Електронный адрес не подтверждён!!! ') }}</strong>
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
                                <div class="col-md-6">
                                    <div class="profile-item profile-item-delivery">
                                        <a class="link-prof-item" data-id-href="tabs-3" href="#">
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
                                <div class="col-md-6">
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
                                <div class="col-md-6">
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
                                <div class="col-md-6"></div>
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
                                        <label>{{__('Страна')}}
                                            <input id="country" oninput="getCountry($(this))" type="text" class="form-control" name="country" value="{{ Auth::user()->country }}" required>
                                        </label>
                                    </li>
                                    <li class="col-sm-12">
                                        <label>{{__('Город')}}
                                            <input id="city" oninput="getCity($(this),'country')" type="text" class="form-control" name="city" value="{{ Auth::user()->city }}" required>
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
                                        <label>{{__('Страна')}}
                                            <input id="delivery_country" oninput="getCountry($(this))" type="text" class="form-control" name="delivery_country" value="@isset($delivery_info){{ $delivery_info->delivery_country }}@endisset" >
                                        </label>
                                    </li>
                                    <li class="col-sm-12">
                                        <label>{{__('Город')}}
                                            <input id="delivery_city" oninput="getCity($(this),'delivery_country')" type="text" class="form-control" name="delivery_city" value="@isset($delivery_info){{ $delivery_info->delivery_city }}@endisset" >
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
                                            <select class="form-control" name="delivery_service" >
                                                <option @isset($delivery_info) @if($delivery_info->delivery_service === 'Новая Почта') selected @endif @endisset value="{{__('Новая Почта')}}">{{__('Новая Почта')}}</option>
                                            </select>
                                        </label>
                                    </li>

                                    <li class="col-sm-12">
                                        <label>{{ __('Номер отделения') }}
                                            <input type="text" class="form-control" name="delivery_department" value="@isset($delivery_info){{ $delivery_info->delivery_department }}@endisset">
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
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addCar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog login-sec" role="document">
            <div class="modal-content">
                <div class="modal-header bg-blue">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h6 class="modal-title text-white" id="myModalLabel">Добавить авто</h6>
                </div>
                <div class="modal-body">
                    <form type="POST" action="{{route('add_car')}}" class="ajax-form ajax1" data-add-block="true" data-id-add-block="addedCars">
                        @csrf
                        <ul class="row login-sec">
                            <li class="col-sm-12">
                                <label>{{ __('VIN код') }}
                                    <input type="text" class="form-control" name="vin_code" value="" required autofocus>
                                </label>
                            </li>
                            <li class="col-sm-12">
                                <label>{{ __('Марка') }}
                                    <select class="form-control selectpicker" data-live-search="true" name="mark" required>
                                        <option label="" value="0"></option>
                                        <option label="ACURA" value="1213">ACURA</option>
                                        <option label="ALFA ROMEO" value="502">ALFA ROMEO</option>
                                        <option label="APRILIA MOTO" value="11546">APRILIA MOTO</option>
                                        <option label="AUDI" value="504">AUDI</option>
                                        <option label="BENELLI MOTO" value="11547">BENELLI MOTO</option>
                                        <option label="BMW" value="511">BMW</option>
                                        <option label="BMW MOTO" value="11578">BMW MOTO</option>
                                        <option label="BMW Mini" value="1231">BMW Mini</option>
                                        <option label="BUELL MOTO" value="11545">BUELL MOTO</option>
                                        <option label="BYD" value="10624">BYD</option>
                                        <option label="CADILLAC" value="852">CADILLAC</option>
                                        <option label="CAGIVA MOTO" value="11548">CAGIVA MOTO</option>
                                        <option label="CHERY" value="10389">CHERY</option>
                                        <option label="CHEVROLET" value="602">CHEVROLET</option>
                                        <option label="CHRYSLER" value="513">CHRYSLER</option>
                                        <option label="CITROEN" value="514">CITROEN</option>
                                        <option label="DACIA" value="603">DACIA</option>
                                        <option label="DAEWOO" value="649">DAEWOO</option>
                                        <option label="DAF" value="516">DAF</option>
                                        <option label="DAIHATSU" value="517">DAIHATSU</option>
                                        <option label="DODGE" value="521">DODGE</option>
                                        <option label="DUCATI MOTO" value="11550">DUCATI MOTO</option>
                                        <option label="FIAT" value="524">FIAT</option>
                                        <option label="FORD" value="525">FORD</option>
                                        <option label="FORD USA" value="814">FORD USA</option>
                                        <option label="GEELY" value="10091">GEELY</option>
                                        <option label="GENERAL MOTORS" value="792">GENERAL MOTORS</option>
                                        <option label="GREAT WALL" value="10405">GREAT WALL</option>
                                        <option label="HONDA" value="533">HONDA</option>
                                        <option label="HONDA MOTO" value="11579">HONDA MOTO</option>
                                        <option label="HUMMER" value="1214">HUMMER</option>
                                        <option label="HUSQVARNA MOTO" value="11738">HUSQVARNA MOTO</option>
                                        <option label="HYOSUNG MOTO" value="11553">HYOSUNG MOTO</option>
                                        <option label="HYUNDAI" value="647">HYUNDAI</option>
                                        <option label="INFINITI" value="1234">INFINITI</option>
                                        <option label="ISUZU" value="538">ISUZU</option>
                                        <option label="IVECO" value="539">IVECO</option>
                                        <option label="JAGUAR" value="540">JAGUAR</option>
                                        <option label="JAWA MOTO" value="11571">JAWA MOTO</option>
                                        <option label="JEEP" value="910">JEEP</option>
                                        <option label="KAWASAKI MOTO" value="11554">KAWASAKI MOTO</option>
                                        <option label="KIA" value="648">KIA</option>
                                        <option label="KTM MOTO" value="11555">KTM MOTO</option>
                                        <option label="LADA" value="545">LADA</option>
                                        <option label="LANCIA (FIAT)" value="546">LANCIA (FIAT)</option>
                                        <option label="LAND ROVER" value="1292">LAND ROVER</option>
                                        <option label="LEXUS" value="874">LEXUS</option>
                                        <option label="MAN" value="551">MAN</option>
                                        <option label="MAZ" value="298">MAZ</option>
                                        <option label="MAZDA" value="552">MAZDA</option>
                                        <option label="MERCEDES" value="553">MERCEDES</option>
                                        <option label="MG ROVER" value="554">MG ROVER</option>
                                        <option label="MITSUBISHI" value="555">MITSUBISHI</option>
                                        <option label="NEOPLAN" value="626">NEOPLAN</option>
                                        <option label="NISSAN" value="558">NISSAN</option>
                                        <option label="OPEL" value="561">OPEL</option>
                                        <option label="PEUGEOT" value="563">PEUGEOT</option>
                                        <option label="PORSCHE" value="565">PORSCHE</option>
                                        <option label="RENAULT" value="566">RENAULT</option>
                                        <option label="RENAULT TRUCKS" value="739">RENAULT TRUCKS</option>
                                        <option label="ROVER" value="568">ROVER</option>
                                        <option label="SATURN" value="1205">SATURN</option>
                                        <option label="SCANIA" value="572">SCANIA</option>
                                        <option label="SEAT" value="573">SEAT</option>
                                        <option label="SKODA" value="575">SKODA</option>
                                        <option label="SMART" value="1149">SMART</option>
                                        <option label="SSANG YONG" value="639">SSANG YONG</option>
                                        <option label="SUBARU" value="576">SUBARU</option>
                                        <option label="SUZUKI" value="577">SUZUKI</option>
                                        <option label="SUZUKI MOTO" value="11582">SUZUKI MOTO</option>
                                        <option label="TOYOTA" value="579">TOYOTA</option>
                                        <option label="TRIUMPH MOTO" value="11583">TRIUMPH MOTO</option>
                                        <option label="VOLVO" value="586">VOLVO</option>
                                        <option label="VOLKSWAGEN" value="587">VOLKSWAGEN</option>
                                        <option label="YAMAHA MOTO" value="11565">YAMAHA MOTO</option>
                                    </select>
                                </label>
                            </li>
                            <li class="col-sm-12">
                                <label>{{ __('Год выпуска') }}
                                    <select class="form-control selectpicker" data-live-search="true" name="year" required>
                                        <option label="" value="0"></option>
                                        @for($i = (integer)date('Y'); $i > 1970; $i--)
                                            <option label="{{$i}}" value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </label>
                            </li>
                            <li class="col-sm-12">
                                <label>{{ __('Модель') }}
                                    <input type="text" class="form-control" name="model" value="">
                                </label>
                            </li>
                            <li class="col-sm-12">
                                <label>{{ __('Обьем двигателя') }}
                                    <input type="text" class="form-control" name="v_motor" value="">
                                </label>
                            </li>
                            <li class="col-sm-12">
                                <label>{{ __('Тип двигателя') }}
                                    <select class="form-control" name="type_motor" required>
                                        <option label="" value="0"></option>
                                        <option label="Бензин" value="1">Бензин</option>
                                        <option label="Дизел" value="2">Дизел</option>
                                        <option label="Газ" value="3">Газ</option>
                                    </select>
                                </label>
                            </li>
                            <li class="col-sm-12 error-response"></li>
                            <li class="col-sm-12 text-left">
                                <button type="submit" class="btn-round">{{__('Добавить')}}</button>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
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
                                               </a>
                            `);
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
        });

        const  getCountry = (obj) => {
            let word = $(obj).val();
            $( "#country,#delivery_country" ).autocomplete({
                source: (request, response) => {
                    $.ajax({
                        url: `http://geohelper.info/api/v1/countries?locale%5Blang%5D=ru&locale%5BfallbackLang%5D=en&filter[name]=${word}&apiKey={{config('app.geo_key')}}`,
                        type: 'GET',
                        success: (data) => {
                            response($.map(data.result, (item) => {
                                return{
                                    value: item.name + ` (${item.iso}/${item.iso3})`,
                                }
                            }));
                        }
                    });
                },
                minLength: 3
            });
        };

        const getCity = (obj,id_country) => {
            let word = $(obj).val();
            let iso =  $( "#" + id_country ).val();
            iso = iso.split(' ',2);
            iso = iso[1].substring(1, iso[1].length-1).split('/',2);
            $( "#city,#delivery_city" ).autocomplete({
                source: (request, response) => {
                    $.ajax({
                        url: `http://geohelper.info/api/v1/cities?locale%5Blang%5D=ru&locale%5BfallbackLang%5D=en&filter[name]=${word}&filter[countryIso]=${iso[0].toLowerCase()}&apiKey={{config('app.geo_key')}}`,
                        type: 'GET',
                        success: (data) => {
                            response($.map(data.result, (item) => {
                                return{
                                    value: item.name,
                                }
                            }));
                        }
                    });
                },
                minLength: 3
            });
        }
    </script>

@endsection