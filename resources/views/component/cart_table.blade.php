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
            @foreach($products as $product)
                <tr>
                    <td><div class="media">
                            <div class="media-left"> <a href="#."> <img class="img-responsive" src="{{asset('images/item-img-1-1.jpg')}}" alt="" > </a> </div>
                            <div class="media-body">
                                <p>{{$product->short_description}}</p>
                            </div>
                        </div></td>
                    <td class="text-center padding-top-60">{{$product->price}} грн</td>
                    <td class="text-center"><!-- Quinty -->

                        <div class="quinty padding-top-20">
                            <input type="number" value="{{$product->count}}">
                        </div></td>
                    <td class="text-center padding-top-60">{{((double)$product->price * (integer)$product->count)}} грн</td>
                    <td class="text-center padding-top-60"><a href="#." class="remove"><i class="fa fa-close"></i></a></td>
                </tr>
            @endforeach
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
        <h5>Grand total: <span>
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
    <a href="#." class="btn-round">Go Payment Methods</a>
</div>