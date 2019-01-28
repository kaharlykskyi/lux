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
                                <div class="panel panel-default margin-top-10">
                                    <div class="panel-heading">{{__('Информация по платежу №' . $status_pay->order_id)}}</div>
                                    <div class="panel-body table-responsive">
                                        <table class="table table-striped">
                                            <caption>{{$status_pay->description}}</caption>
                                            <tbody>
                                                <tr>
                                                    <th class="">{{__('Id платежа в системе LiqPay')}}</th>
                                                    <td>{{$status_pay->acq_id}}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{__('Id платежа в магазине')}}</th>
                                                    <td>{{$status_pay->order_id}}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{__('Сумма платежа')}}</th>
                                                    <td>{{$status_pay->amount}}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{__('Комиссия с отправителя в валюте')}}</th>
                                                    <td>{{$status_pay->commission_credit}}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{__('Зачислено на баланс')}}</th>
                                                    <td>{{$status_pay->amount - $status_pay->sender_commission - $status_pay->receiver_commission - $status_pay->commission_credit - $status_pay->commission_debit}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @break
                            @case('success')
                                <div class="alert alert-success text-center" role="alert">
                                    {{__('Платеж прошол успешно!')}}
                                </div>
                                <div class="panel panel-default margin-top-10">
                                    <div class="panel-heading">{{__('Информация по платежу №' . $status_pay->order_id)}}</div>
                                    <div class="panel-body table-responsive">
                                        <table class="table table-striped">
                                            <caption>{{$status_pay->description}}</caption>
                                            <tbody>
                                            <tr>
                                                <th class="">{{__('Id платежа в системе LiqPay')}}</th>
                                                <td>{{$status_pay->acq_id}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{__('Id платежа в магазине')}}</th>
                                                <td>{{$status_pay->order_id}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{__('Сумма платежа')}}</th>
                                                <td>{{$status_pay->amount}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{__('Комиссия с отправителя в валюте')}}</th>
                                                <td>{{$status_pay->commission_credit}}</td>
                                            </tr>
                                            <tr>
                                                <th>{{__('Зачислено на баланс')}}</th>
                                                <td>{{$status_pay->amount - $status_pay->sender_commission - $status_pay->receiver_commission - $status_pay->commission_credit - $status_pay->commission_debit}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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