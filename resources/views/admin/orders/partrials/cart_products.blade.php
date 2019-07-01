
    <div class="table-responsive">
        <table class="table table-borderless table-data3">
            <thead>
            <tr>
                <th>Название</th>
                <th>Артикль</th>
                <th>
                    Цена Магазина/<br>
                    Поставщика
                </th>
                <th>Количество</th>
                <th></th>
            </tr>
            </thead>
            <tbody id="order-product-block">
            @isset($order->cartProduct)
            @foreach($order->cartProduct as $item)
                <tr>
                    <td>
                        {{$item->name}}
                        @isset($item->stocks)
                            <br>
                            @php $stocks_decode = json_decode($item->stocks);@endphp
                            @foreach($stocks_decode as $k => $stocks)
                                <span class="small">{{$k}} - {{$stocks}};</span>
                            @endforeach
                        @endisset
                    </td>
                    <td>{{$item->articles}}</td>
                    <td>
                        {{(int)$item->price}}грн.<br>
                        <span class="small">{{$item->provider_price}} {{$item->provider_currency}}</span>
                    </td>
                    <td>{{$item->pivot['count']}}</td>
                    <td>
                        <div class="table-data-feature">
                            <button onclick="deleteProductOrder('{{$item->pivot['id']}}')" type="button" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Удалить')}}">
                                <i class="zmdi zmdi-delete"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            @endisset
            </tbody>
        </table>
    </div>
