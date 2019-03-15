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
                        <td>{{($item->status === 1)?__('успешно'):__('отказ')}}</td>
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