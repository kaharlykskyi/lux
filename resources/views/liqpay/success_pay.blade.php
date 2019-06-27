@extends('layouts.app')

@section('content')

    <div id="content">
        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => 'успешное пополнение баланса']
            ]
        ])
        @endcomponent

        <section class="contact-sec padding-top-40 padding-bottom-80">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-offset-3 col-sm-6">
                        @switch($status_pay->status)
                            @case('error')
                                <div class="alert alert-danger text-center" role="alert">
                                    {{__('Платеж был отменен или неверно введены данные!')}}
                                </div>
                                @break
                            @case('sandbox')
                                <div class="alert alert-warning text-center" role="alert">
                                    {{__('Тестовый платеж!')}}
                                </div>
                                @include('liqpay.partirals.info_pay')
                                @break
                            @case('success')
                                <div class="alert alert-success text-center" role="alert">
                                    {{__('Платеж прошол успешно!')}}
                                </div>
                                @include('liqpay.partirals.info_pay')
                                @break
                            @case('processing')
                                <div class="alert alert-warning text-center" role="alert">
                                    {{__('Платёж на обработке')}}
                                </div>
                                @break
                            @case('wait_secure')
                                <div class="alert alert-warning text-center" role="alert">
                                    {{__('Платеж на проверке')}}
                                </div>
                                @break
                            @case('Платеж создан, ожидается его завершение отправителем')
                                <div class="alert alert-warning text-center" role="alert">
                                    {{__('Платеж на проверке')}}
                                </div>
                                @break
                        @endswitch
                    </div>
                </div>
            </div>

        </section>
    </div>


@endsection
