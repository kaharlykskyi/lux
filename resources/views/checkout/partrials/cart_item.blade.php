<section class="shopping-cart padding-bottom-30">
    <div class="table-responsive" id="checkout-cart-block">
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
                                <div class="media-left"> <a href="{{route('product',$product->articles)}}"> <img class="img-responsive" src="{{asset('images/item-img-1-1.jpg')}}" alt="{{$product->name}}" > </a> </div>
                            </div></td>
                        <td class="text-center padding-top-60">{{$product->price}} грн</td>
                        <td class="text-center"><!-- Quinty -->

                            <div class="quinty padding-top-20">
                                <input id="count{{$product->id}}" type="number" value="{{$product['pivot']['count']}}" oninput="changeCount({{$product->id}},{{$product['pivot']['cart_id']}},'{{route('product_count')}}')">
                            </div></td>
                        <td class="text-center padding-top-60" id="price{{$product->id}}">{{((double)$product->price * (integer)$product['pivot']['count'])}} грн</td>
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

        @php
            $sum = 0.00;
            if (isset($products)){
                foreach ($products as $product){
                    $sum += (double)$product->price * (integer)$product['pivot']['count'];
                }
            }
        @endphp
        <h6 class="text-right text-black text-uppercase">{{__('Общая сумма: ')}}
            @if(isset($user->discount))
                <span id="total-price-checkout">
                                        {{round($sum - ($sum * (int)$user->discount->percent / 100),2)}}
                                    </span>грн
                <span class="margin-left-10 small text-line-through" id="total-not-discount">
                                        {{$sum}}грн <i class="fa fa-question" aria-hidden="true" title="{{$user->discount->description}}"></i>
                                    </span>
            @else
                <span id="total-price-checkout">
                                        {{$sum}}
                                    </span>грн
            @endif
        </h6>
    </div>
</section>