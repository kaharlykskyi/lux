@isset($order->cartProduct)
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
            </tr>
            </thead>
            <tbody>
            @foreach($order->cartProduct as $item)
                <tr>
                    <td>
                        {{--<span class="m-r-10">
                            <i onclick="" class="fa fa-info" style="cursor: pointer" title="Показать аналогичные товары"></i>
                        </span>--}}
                        {{$item->name}}
                    </td>
                    <td>{{$item->articles}}</td>
                    <td>
                        {{$item->price}}грн.<br>
                        @php
                            $provider_price = 0;
                            if ($item->price < 2000){
                                $provider_price = $item->price - $item->price * 0.2;
                            } elseif ($item->price >= 2000 && $item->price <= 5000){
                                $provider_price = $item->price - $item->price * 0.15;
                            } elseif ($item->price > 5000){
                                $provider_price = $item->price - $item->price * 0.1;
                            }
                        @endphp
                        <span class="small">{{$provider_price}}грн.</span>
                    </td>
                    <td>{{$item->pivot['count']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endisset
