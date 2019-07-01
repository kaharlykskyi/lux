@isset($order->client)
    <div class="table-responsive">
        <table class="table">
            <tbody>
            <tr>
                <th>{{__('ФИО')}}</th>
                <td>{{$order->client->fio}}</td>
            </tr>
            <tr>
                <th>{{__('E-mail')}}</th>
                <td>{{$order->client->email}}</td>
            </tr>
            <tr>
                <th>{{__('Телефон')}}</th>
                <td>{{$order->client->phone}}</td>
            </tr>
            <tr>
                <th>{{__('Страна')}}</th>
                <td>{{isset($order->client->deliveryInfo)?$order->client->deliveryInfo->delivery_country:''}}</td>
            </tr>
            <tr>
                <th>{{__('Город')}}</th>
                <td>{{isset($order->client->deliveryInfo)?$order->client->deliveryInfo->delivery_city:''}}</td>
            </tr>
            <tr>
                <th>{{__('Служба доставки')}}</th>
                <td>{{isset($order->client->deliveryInfo)?trans('custom.'.$order->client->deliveryInfo->delivery_service):''}}</td>
            </tr>
            <tr>
                <th>{{__('Отделение почты')}}</th>
                <td>{{isset($order->client->deliveryInfo)?$order->client->deliveryInfo->delivery_department:''}}</td>
            </tr>
            <tr>
                <th>{{__('Номер накладной')}}</th>
                <td>
                    <div class="form-group">
                        <input onblur="saveInvoice('{{$order->id}}',this)" name="invoice_np" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{$order->invoice_np}}">
                    </div>
                </td>
            </tr>
            <tr>
                <th>{{__('Статус заказа')}}</th>
                <td>
                    <div style="width: 90%;" class="rs-select2--dark rs-select2--md m-r-10 rs-select2--border">
                        <select class="js-select2" name="order_status_code" onchange="orderStatus('{{$order->id}}',this)">
                            @isset($order_code)
                                @foreach($order_code as $v)
                                    <option @if($v->id === $order->oder_status) selected @endif value="{{$v->id}}">{{$v->name}}</option>
                                @endforeach
                            @endisset
                        </select>
                        <div class="dropDownSelect2"></div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
@endisset
