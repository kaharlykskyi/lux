
<!-- Short List -->
<div class="short-lst">
    <ul>
        <!-- Short List -->
        @if(method_exists($catalog_products,'total'))
            <li>
                <p>{{(isset($catalog_products)?$catalog_products->total():'0') . __(' найдено товаров')}}</p>
            </li>
        @endif
        <!-- Short  -->
        <li >
            <select class="selectpicker" onchange="$.get(`{{route('filter')}}?pre_show=${$(this).val()}`,()=>{location.reload()});">
                <option @if(session('pre_products') === 12) selected @endif value="12">Показывать 12 </option>
                <option @if(session('pre_products') === 24) selected @endif  value="24">Показывать 24 </option>
                <option @if(session('pre_products') === 32) selected @endif  value="32">Показывать 32 </option>
            </select>
        </li>
        <li >
            <select class="selectpicker" onchange="$.get(`{{route('filter')}}?price_sort=${$(this).val()}`,()=>{location.reload()});">
                <option @if(session('price_sort') === 'ASC') selected @endif value="ASC">По возрастанию цены </option>
                <option @if(session('price_sort') === 'DESC') selected @endif  value="DESC">По убыванию цены </option>
            </select>
        </li>
    </ul>
</div>

<!-- Items -->
<div class="item-col-4 catalog">

@isset($catalog_products)
    @forelse($catalog_products as $product)
        <!-- Product -->
            <div class="product" @isset($product->id)id="product-{{$product->id}}"@endisset>
                <article>
                    @php
                        $file_path = asset('images/default-no-image_2.png');
                        if (isset($files)){
                            foreach ($files as $file){
                                if ($product->articles === $file->DataSupplierArticleNumber && (int)$product->supplierId === (int)$file->SupplierId){
                                    $brand_folder = explode('_',$file->PictureName);
                                    $file_path = asset('product_imags/'.$brand_folder[0].'/'.str_ireplace(['.BMP','.JPG'],'.jpg',$file->PictureName));
                                }
                            }
                        }else{
                            if (!empty($product->file)){
                                $brand_folder = explode('_',$product->file);
                                $file_path = asset('product_imags/'.$brand_folder[0].'/'.str_ireplace(['.BMP','.JPG'],'.jpg',$product->file));
                            }
                        }
                    @endphp
                    <div class="product-img" style="background-image: url('{{$file_path}}');"></div>
                    <!-- Content -->
                    <span class="tag">{{$product->matchcode}}</span> <a href="{{route('product',$product->id)}}" class="tittle">
                        {{mb_strimwidth($product->name,0,30,' ...')}}
                    </a>
                    <p class="rev"></p>
                    @isset($product->price)<div class="price">{{(int)$product->price . __(' грн')}} </div>@endisset
                    @if($product->count > 0 && isset($product->id))
                        <a href="#." onclick="addCart('{{route('add_cart',$product->id)}}')" class="cart-btn custom-cart-btn">Купить</a>
                    @else
                        <a href="#." onclick="alert('нет в наличии')" class="cart-btn custom-cart-btn">Купить</a>
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
