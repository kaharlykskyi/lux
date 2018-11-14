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
                <tr id="tr_product{{$product->id}}">
                    <td><div class="media">
                            <div class="media-left"> <a href="#."> <img class="img-responsive" src="{{asset('images/item-img-1-1.jpg')}}" alt="" > </a> </div>
                            <div class="media-body">
                                <p>{{$product->short_description}}</p>
                            </div>
                        </div></td>
                    <td class="text-center padding-top-60">{{$product->price}} грн</td>
                    <td class="text-center"><!-- Quinty -->

                        <div class="quinty padding-top-20">
                            <input id="count{{$product->id}}" type="number" value="{{$product->count}}" oninput="changeCount({{$product->id}},{{$product->cart_id}},'{{route('product_count')}}')">
                        </div></td>
                    <td class="text-center padding-top-60" id="price{{$product->id}}">{{((double)$product->price * (integer)$product->count)}} грн</td>
                    <td class="text-center padding-top-60"><a href="#." class="remove" onclick="deleteProduct({{$product->id}},{{$product->cart_id}},'{{route('product_delete')}}'); return false;"><i class="fa fa-close"></i></a></td>
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

<!-- Promotion -->
<div class="promo">
    <div class="coupen">
        <label> Promotion Code
            <input type="text" placeholder="Your code here">
            <button type="submit"><i class="fa fa-arrow-circle-right"></i></button>
        </label>
    </div>

    <!-- Grand total -->
    <div class="g-totel">
        <h5>{{__('Общая сумма: ')}} <span>
                @php
                    $sum = 0.00;
                        if (isset($products)){
                            foreach ($products as $product){
                                $sum += (double)$product->price * (integer)$product->count;
                            }
                        }
                @endphp
                {{$sum}} грн
            </span></h5>
    </div>
</div>

<!-- Button -->
<div class="pro-btn">
    <a href="#." class="btn-round btn-light" data-dismiss="modal">{{__('Продолжить покупки')}}</a>
    <a href="#." class="btn-round">{{__('Оформление заказа')}}</a>
</div>