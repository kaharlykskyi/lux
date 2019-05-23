<div class="row table-responsive hidden-xs">
    <table class="table">
        <thead>
        <tr>
            <th>Товар</th>
            <th class="text-center">Цена</th>
            <th class="text-center">Количество</th>
            <th class="text-center">Общая цена</th>
            <th>&nbsp; </th>
        </tr>
        </thead>
        <tbody>
        <!-- Item Cart -->
        @isset($products)
            @forelse($products as $product)
                <tr id="tr_product{{$product->product->id}}">
                    <td>
                        <a href="{{route('product',$product->product->articles)}}">
                            <img class="cart-img" src="{{asset('images/item-img-1-1.jpg')}}" alt="{{$product->product->name}}" >
                            <p class="text-center">{{$product->product->name}}</p>
                        </a>
                    </td>
                    <td class="text-center padding-top-60">{{(int)$product->product->price}} грн</td>
                    <td class="text-center"><!-- Quinty -->

                        <div class="quinty padding-top-20">
                            <input id="count{{$product->product->id}}" type="number" value="{{$product->count}}" oninput="changeCount({{$product->product->id}},{{$product->cart_id}},'{{route('product_count')}}')">
                        </div></td>
                    <td class="text-center padding-top-60" id="price{{$product->product->id}}">{{((int)$product->product->price * (integer)$product->count)}} грн</td>
                    <td class="text-center padding-top-60"><a href="#." class="remove" onclick="deleteProduct({{$product->product->id}},{{$product->cart_id}},'{{route('product_delete')}}'); return false;"><i class="fa fa-close"></i></a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="alert alert-info margin-15" role="alert">
                            Похоже вы еще не добавляли товары в корзину, <strong>начните прямо сейчас</strong>
                        </div>
                    </td>
                </tr>
            @endforelse
        @endisset
        </tbody>
    </table>
</div>

<div class="row hidden-sm hidden-md hidden-lg">
    <div class="col-sm-12 mob-cart">
        @isset($products)
            <ul class="list-group">
                @forelse($products as $product)
                    <li class="list-group-item" id="li_product{{$product->product->id}}">
                        <div class="media">
                            <div class="media-left"> <a href="{{route('product',$product->product->articles)}}"> <img class="img-responsive" src="{{asset('images/item-img-1-1.jpg')}}" alt="{{$product->product->name}}" > </a> </div>
                            <div class="media-body hidden-sm hidden-xs">
                                <p>{{$product->product->short_description}}</p>
                            </div>
                        </div>
                        <div>{{(int)$product->product->price}} грн <span class="show-480">{{__('  - за 1 единицу')}}</span></div>
                        <div class="quinty">
                            <input id="count-mob{{$product->product->id}}" type="number" value="{{$product->count}}" oninput="changeCount({{$product->product->id}},{{$product->cart_id}},'{{route('product_count')}}')">
                        </div>
                        <div id="price-mob{{$product->product->id}}">
                            {{((int)$product->product->price * (integer)$product->count)}} грн <span class="show-480">{{__(' - общяя стоимость')}}</span>
                        </div>
                        <div>
                            <a href="#." class="remove" onclick="deleteProduct({{$product->product->id}},{{$product->cart_id}},'{{route('product_delete')}}'); return false;"><i class="fa fa-close"></i></a>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item">
                        <div class="alert alert-info margin-15" role="alert">
                            Похоже вы еще не добавляли товары в корзину, <strong>начните прямо сейчас</strong>
                        </div>
                    </li>
                @endforelse
            </ul>
        @endisset
    </div>
</div>

<h6 class="text-right text-black text-uppercase">{{__('Общая сумма: ')}}
    <span id="total-price-modal">
                @php
                    $sum = 0.00;
                        if (isset($products)){
                            foreach ($products as $product){
                                $sum += (double)$product->product->price * (integer)$product->count;
                            }
                        }
                @endphp
        {{(int)$sum}} грн
            </span>
</h6>

<!-- Button -->
<div class="pro-btn">
    <a href="#." class="btn-round btn-light margin-top-10" data-dismiss="modal">{{__('Продолжить покупки')}}</a>
    <a href="{{route('checkout')}}" class="btn-round  margin-top-10">{{__('Оформление заказа')}}</a>
</div>
