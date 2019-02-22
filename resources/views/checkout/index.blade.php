@extends('layouts.app')

@section('content')

    <!-- Content -->
    <div id="content">

        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => 'Оформление заказа']
            ]
        ])
        @endcomponent

        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    @include('checkout.partrials.cart_item')
                </div>
                <div class="col-md-4">
                @guest
                    @include('checkout.partrials.guest_form')
                @else
                     @include('checkout.partrials.register_form')
                @endguest
                </div>
            </div>
        </div>


    </div>
    <!-- End Content -->
    <script>
        $(document).ready(function () {
            if ($('#delivery-service').val() === 'novaposhta' && $('#city').val().length > 0){
                $('.delivery-dep').show();
            }

            $('#delivery-service').change(function () {
                if ($(this).val() === 'novaposhta' && $('#city').val().length > 0){
                    $('.delivery-dep').show();
                } else {
                    $('.delivery-dep').hide();
                }
            });

            $('#city').blur(function () {
                if ($('#delivery-service').val() === 'novaposhta' && $(this).val().length > 0){
                    $('.delivery-dep').show();
                } else {
                    $('.delivery-dep').hide();
                }
            });

            $('#delivery_department').on('input',function () {
                const flag = ($('#delivery-service').val() === 'novaposhta');
                if (flag && $(this).val().length > 0){
                    const city = $('#city').val();
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

            $('#new_user form').submit(function (e) {
               e.preventDefault();
               $.post($(this).attr('action'),$(this).serialize(),function (data) {
                   if (data.errors !== undefined){
                       let errors_html =  ``;
                       for (let key in data.errors){
                           errors_html += `${data.errors[key][0]}\n`;
                       }
                       alert(errors_html);
                   }
                   location.href = '/'
               });
            });

            if ($('#city').val().length > 0 && $('#delivery_department').val().length < 1){
                getPlacePost('city');
            }
            if($('#delivery_department').val().length > 0) {
                getPostOfice('city');
            }

            $('#city').blur(function () {
                getPlacePost('city');
            });

            $('#delivery_department').blur(function () {
                if ($(this).val().length > 0){
                    getPostOfice('city');
                }
            });

            $('#pay_method').change(function () {
                const balance = {{isset($user->balance)?$user->balance->balance:0}};
                const total = $('#total-price-checkout').text();
                if ($(this).val() === 'online'){
                    if (parseFloat(total) > balance){
                        alert('Сума на вашем балансе меньше чем общяя стоимость корзины. Пополните баланс или смените способ оплаты');
                    }
                }
            });
        });
    </script>

@endsection