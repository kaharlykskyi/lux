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
                <th></th>
            </tr>
            </thead>
            <tbody id="order-product-block">
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
            </tbody>
        </table>
    </div>
@endisset
