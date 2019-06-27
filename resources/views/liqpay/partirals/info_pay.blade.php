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
