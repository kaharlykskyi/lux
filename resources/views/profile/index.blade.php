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
                            <a class="text-danger" style="text-decoration: underline !important;" href="{{ route('verification.resend') }}">
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
                                <li id="nav-tabs-1" data-id-href="tabs-1" class="list-group-item">{{__('Личные данные')}}</li>
                                <li id="nav-tabs-3" data-id-href="tabs-3" class="list-group-item">{{__('Информация о доставке')}}</li>
                                <li id="nav-tabs-2" data-id-href="tabs-2" class="list-group-item">{{__('Заказы')}}</li>
                                <li id="nav-tabs-4" data-id-href="tabs-4" class="list-group-item">{{__('Мои автомобили')}}</li>
                                <li id="nav-tabs-5" data-id-href="tabs-5" class="list-group-item">{{__('Смена пароля')}}</li>
                                <li id="nav-tabs-6" data-id-href="tabs-6" class="list-group-item">{{__('Мои возвраты')}}</li>
                                <li id="nav-tabs-7" data-id-href="tabs-7" class="list-group-item">{{__('Баланс')}}</li>
                                <li id="nav-tabs-8" data-id-href="tabs-8" class="list-group-item">{{__('Взаиморасчеты')}}</li>
                                <li id="nav-tabs-9" data-id-href="tabs-9" class="list-group-item">{{__('Отследить заказ')}}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 tab-item active">
                    @include('profile.partirals.profile_panel')
                </div>
                <div class="col-md-9 tab-item" id="tabs-1">
                    @include('profile.partirals.user_info')
                </div>
                <div class="col-md-9 tab-item" id="tabs-2">
                    @include('profile.partirals.orders')
                </div>
                <div class="col-md-9 tab-item" id="tabs-3">
                    @include('profile.partirals.delivery_info')
                </div>
                <div class="col-md-9 tab-item" id="tabs-4">
                    @include('profile.partirals.user_cars')
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
                    @include('profile.partirals.balance')
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
                <div class="col-md-9 tab-item" id="tabs-9">
                    @include('profile.partirals.track_order')
                </div>
            </div>
        </div>
    </div>

    @component('profile.component.add_car_model')@endcomponent

    @component('profile.component.add_phone')@endcomponent

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
                                <a href="#" class="list-group-item" id="car-block${data.response.id}">
                                     <p class="text-right">
                                         <button class="delete-car-btn" onclick="deleteCar(${data.response.id})" title="Удалить машину">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                         </button>
                                     </p>
                                     <p class="list-group-item-text">VIN код: ${data.response.vin_code}</p>
                                     <p class="list-group-item-text">Тип: ${data.response.type_auto}</p>
                                     <p class="list-group-item-text">Год выпуска: ${data.response.year_auto}</p>
                                     <p class="list-group-item-text">Марка: ${data.response.brand_auto}</p>
                                     <p class="list-group-item-text">Модель: ${data.response.model_auto}</p>
                                     <p class="list-group-item-text">Модификация: ${data.response.modification_auto}</p>
                                     <p class="list-group-item-text">Тип кузова: ${data.response.body_auto}}</p>
                                     <p class="list-group-item-text">Тип двигателя: ${data.response.type_motor}</p>
                                </a>`);
                        }
                        $('#year_auto,#brand_auto,#model_auto,#modification_auto,#body_auto,#type_motor').addClass('hidden');
                        $('#add-car-btn').attr('disabled','disabled').css('cursor','not-allowed');
                        $('#addCar').modal('hide');
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

        function deleteCar(id) {
            if(confirm('Удалить машину?')){
                $.post('{{route('delete_car')}}',{'id':id,'_token':'{{csrf_token()}}'}).success(function () {
                    $(`#car-block${id}`).remove();
                });
            }
            return false;
        }
    </script>

@endsection