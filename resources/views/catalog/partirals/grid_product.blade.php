
<!-- Short List -->
<div class="short-lst">
    <ul>
        <!-- Short List -->
        <li>
            <p>{{(isset($catalog_products)?$catalog_products->total():'0') . __(' найдено товаров')}}</p>
        </li>
        <!-- Short  -->
        <li >
            <select class="selectpicker" onchange="$.get(`{{route('filter')}}?pre_show=${$(this).val()}`,()=>{location.reload()});">
                <option @if(session('pre_products') === 12) selected @endif value="12">Показывать 12 </option>
                <option @if(session('pre_products') === 24) selected @endif  value="24">Показывать 24 </option>
                <option @if(session('pre_products') === 32) selected @endif  value="32">Показывать 32 </option>
            </select>
        </li>
    </ul>
</div>

<!-- Items -->
<div class="item-col-4">

@isset($catalog_products)
    @forelse($catalog_products as $product)
        <!-- Product -->
            <div class="product" @isset($product->id)id="product-{{$product->id}}"@endisset>
                <article>
                    <img class="img-responsive" src="{{asset('/images/item-img-1-2.jpg')}}" alt="" >
                    <!-- Content -->
                    <span class="tag">{{$product->matchcode}}</span> <a href="{{route('product',str_replace('/','@',(isset($product->articles)?$product->articles:$product->DataSupplierArticleNumber)))}}?supplierid={{$product->supplierId}}&product_id={{$product->id}}" class="tittle">
                        {{mb_strimwidth($product->name,0,30,' ...')}}
                    </a>
                    <p class="rev"></p>
                    @isset($product->price)<div class="price">{{$product->price . __(' грн')}} </div>@endisset
                    @if($product->count > 0)
                        <a href="#." onclick="addCart('{{route('add_cart',$product->id)}}')" class="cart-btn"><i class="icon-basket-loaded"></i></a>
                    @else
                        <a href="#." onclick="alert('нет в наличии')" class="cart-btn"><i class="icon-basket-loaded"></i></a>
                    @endif</article>
            </div>
        @empty
            <div class="alert alert-warning" role="alert">{{__('Поиск не дал результатов')}}</div>
        @endforelse
    @endisset

    <div class="row">
        <div class="col-xs-12">
            <!-- pagination -->
            @isset($catalog_products){{$catalog_products->links()}}@endisset
        </div>
    </div>
</div>
