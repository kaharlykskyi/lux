<section class="shopping-cart padding-bottom-30">
    <div id="checkout-cart-block">
        <table class="table hidden-xs">
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
                        <td><div class="media">
                                <div class="media-left">
                                    <a href="{{route('product',['alias' => $product->product->id])}}">
                                        @php $file =DB::table('products')
                                                ->select(DB::raw('
                                                    (SELECT a_img.PictureName
                                                        FROM '.config('database.connections.mysql_tecdoc.database').'.article_images AS a_img
                                                        WHERE a_img.DataSupplierArticleNumber=products.articles AND a_img.SupplierId=products.brand LIMIT 1) AS file
                                                '))
                                                ->where('products.articles',$product->product->articles)
                                                ->get();
                                        @endphp
                                        @if(isset($file[0]))
                                            @php $brand_folder = explode('_',$file[0]->file) @endphp
                                            <img class="img-responsive" src="{{asset('product_imags/'.$brand_folder[0].'/'.str_ireplace(['.BMP','.JPG'],'.jpg',$file[0]->file))}}" alt="{{$product->product->name}}" >
                                        @else
                                            <img  class="img-responsive" src="{{asset('images/default-no-image_2.png')}}" alt="{{$product->product->name}}" >
                                        @endif
                                    </a>
                                </div>
                            </div></td>
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

        <div class="row hidden-sm hidden-md hidden-lg">
            <div class="col-sm-12 mob-cart">
                @isset($products)
                    <ul class="list-group">
                        @forelse($products as $product)
                            <li class="list-group-item" id="li_product{{$product->product->id}}">
                                <div class="media">
                                    <div class="media-left"> <a href="{{route('product',['alias' => $product->product->id])}}">
                                            @php $file =DB::table('products')
                                                ->select(DB::raw('
                                                    (SELECT a_img.PictureName
                                                        FROM '.config('database.connections.mysql_tecdoc.database').'.article_images AS a_img
                                                        WHERE a_img.DataSupplierArticleNumber=products.articles AND a_img.SupplierId=products.brand LIMIT 1) AS file
                                                '))
                                                ->where('products.articles',$product->product->articles)
                                                ->get();
                                            @endphp
                                            @if(isset($file[0]))
                                                @php $brand_folder = explode('_',$file[0]->file) @endphp
                                                <img class="img-responsive" src="{{asset('product_imags/'.$brand_folder[0].'/'.str_ireplace(['.BMP','.JPG'],'.jpg',$file[0]->file))}}" alt="{{$product->product->name}}" >
                                            @else
                                                <img  class="img-responsive" src="{{asset('images/default-no-image_2.png')}}" alt="{{$product->product->name}}" >
                                            @endif
                                        </a> </div>
                                    <div class="media-body hidden-sm hidden-xs">
                                        <p>{{$product->product->short_description}}</p>
                                    </div>
                                </div>
                                <div>{{(int)$product->product->price}} грн <span class="show-480">{{__('  - за 1 единицу')}}</span></div>
                                <div class="quinty">
                                    <input id="count-mob{{$product->product->id}}" type="number" value="{{$product->count}}" oninput="changeCount({{$product->product->id}},{{$product->cart_id}},'{{route('product_count')}}')">
                                </div>
                                <div id="price-mob{{$product->id}}">
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

        @php
            $sum = 0.00;
            if (isset($products)){
                foreach ($products as $product){
                    $sum += (double)$product->product->price * (integer)$product->count;
                }
            }
        @endphp
        <h6 class="text-right text-black text-uppercase">{{__('Общая сумма: ')}}
            @if(isset($user->discount))
                <span id="total-price-checkout">
                    {{(int)$sum - (int)($sum * (int)$user->discount->percent / 100)}}
                </span>грн
                <span class="margin-left-10 small text-line-through" id="total-not-discount">
                    {{(int)$sum}}грн <i class="fa fa-question" aria-hidden="true" title="{{$user->discount->description}}"></i>
                </span>
            @else
                <span id="total-price-checkout">
                    {{(int)$sum}}
                </span>грн
            @endif
        </h6>
    </div>
</section>
