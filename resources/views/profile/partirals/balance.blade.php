<div class="panel panel-primary">
    <div class="panel-heading">{{__('Баланс')}}</div>
    <div class="panel-body panel-profile">
        <div class="col-sm-12">
            <ul class="row login-sec">
                <li class="col-sm-6">
                    <p class="h4">Баланс: <strong>@if(isset($balance)){{floatval($balance->balance)}}@else{{__('0.00')}}@endif</strong> грн</p>
                </li>
                <li class="col-sm-6 text-right">
                    <button type="button" onclick="location.href = '{{route('liqpay')}}'" class="btn-round">{{__('Пополнить баланс')}}</button>
                </li>
            </ul>
        </div>
        <div class="col-sm-12">
            <table class="table">
                <caption>{{__('История пополнений')}}</caption>
                <thead>
                <tr>
                    <th>{{__('ID')}}</th>
                    <th>{{__('Дата')}}</th>
                    <th>{{__('Сумма')}}</th>
                    <th>{{__('Статус')}}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($balance_history as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->created_at}}</td>
                        <td>{{$item->balance_refill}}</td>
                        <td>{{($item->status === 1)?__('успешно'):__('отказ')}}
                            <span onclick="statusPay('{{$item->id}}')" title="{{__('Информация по оплате')}}"  style="cursor: pointer" class="margin-left-5"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <div class="alert alert-info margin-15" role="alert">
                                {{__('Платежи ещё не производились')}}
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function statusPay(id) {
        $('#statusPayModal .modal-body').html(`<p class="text-center">
                                                <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                                <span class="sr-only">Loading...</span>
                                            </p>`);
        $('#statusPayModal .modal-title').text(`Информация по патежу №${id}`);
        $('#statusPayModal').modal('show');
        $.get(`{{route('liqpay.status_pay')}}?id=${id}`,function (data) {
            console.log(data);
            if (data.liqpay_data.status === 'error'){
                $('#statusPayModal .modal-body').html('<div class="alert alert-danger" role="alert"><p class="text-center">{{__('Платёж небыл произведён')}}</p></div>');
                return false;
            }
            if ((data.liqpay_data.status === 'success' || data.liqpay_data.status === 'sandbox') && data.pay.status === 0){
                $('#statusPayModal .modal-body').html(`
                    <div class="alert alert-warning" role="alert">
                        <p class="text-center">{{__('Платёж произведен, но не зачислен на баланс. Сообщите следующие данные в пооддежку сайта.')}}</p>
                        <div class="panel-body table-responsive">
                             <table class="table">
                                  <tbody>
                                       <tr>
                                           <th class="">{{__('Id платежа в системе LiqPay')}}</th>
                                           <td>${data.liqpay_data.acq_id}</td>
                                       </tr>
                                       <tr>
                                           <th>{{__('Id платежа в магазине')}}</th>
                                           <td>${data.liqpay_data.order_id}</td>
                                       </tr>
                                       <tr>
                                           <th>{{__('Id пользователя')}}</th>
                                           <td>{{Auth::id()}}</td>
                                       </tr>
                                       <tr>
                                           <th>{{__('Имя пользователя')}}</th>
                                           <td>{{Auth::user()->name}}</td>
                                       </tr>
                                  </tbody>
                             </table>
                    </div>
                `);
                return false;
            }
            $('#statusPayModal .modal-body').html(`
                                    <div class="panel-body table-responsive">
                                        <table class="table table-striped">
                                            <caption>${data.liqpay_data.description}</caption>
                                            <tbody>
                                                <tr>
                                                    <th class="">{{__('Id платежа в системе LiqPay')}}</th>
                                                    <td>${data.liqpay_data.acq_id}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{__('Id платежа в магазине')}}</th>
                                                    <td>${data.liqpay_data.order_id}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{__('Сумма платежа')}}</th>
                                                    <td>${data.liqpay_data.amount}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{__('Комиссия с отправителя в валюте')}}</th>
                                                    <td>${data.liqpay_data.commission_credit}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{__('Зачислено на баланс')}}</th>
                                                    <td>${Number((data.liqpay_data.amount - data.liqpay_data.sender_commission - data.liqpay_data.receiver_commission - data.liqpay_data.commission_credit - data.liqpay_data.commission_debit).toFixed(2))}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>`);
        });
    }
</script>

<div class="modal fade" tabindex="-1" role="dialog" id="statusPayModal" aria-labelledby="statusPayModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button style="background-color: #337ab7;" type="button" class="btn btn-default" data-dismiss="modal">{{__('Закрыть')}}</button>
            </div>
        </div>
    </div>
</div>