<div class="panel panel-primary">
    <div class="panel-heading">{{__('Мои заказы')}}</div>
    <div class="panel-body panel-profile">
        <div class="row login-sec">
            <div class="col-xs-12">

                <table class="table">
                    <caption>{{__('Мои заказы')}}</caption>
                    <thead>
                    <tr>
                        <th>{{__('ID')}}</th>
                        <th>{{__('Дата')}}</th>
                        <th>{{__('Сумма')}}</th>
                        <th>{{__('Статус')}}</th>
                        <th>{{__('Дата доставки')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{$order->id}}</td>
                            <td>{{$order->updated_at}}</td>
                            <td>{{(int)$order->total_price . __(' грн.')}}</td>
                            <td>
                                {{$order->status}}
                                <span class="margin-left-5">
                                                        <a href="{{route('track_order',$order->id)}}" title="Отследить посылку">
                                                            <i class="fa fa-truck" aria-hidden="true"></i>
                                                        </a>
                                                    </span>
                            </td>
                            <td>
                                @isset($order->track_data){{$order->track_data['ScheduledDeliveryDate']}}@endisset
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="alert alert-info margin-15" role="alert">
                                    Похоже вы еще не делали заказы, <strong>начните прямо сейчас</strong>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
