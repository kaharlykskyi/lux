@if(isset($accessories) && count($accessories) > 0)
    <section class="padding-top-30 padding-bottom-0">
        <div class="heading">
            <h2>{{__('Сопутсвующие товары')}}</h2>
            <hr>
        </div>
        <div class="item-slide-4 with-nav">
            @foreach($accessories as $accessory)
                <div class="product">
                    <article>
                        <img class="img-responsive" src="images/item-img-1-1.jpg" alt="" >
                        @if(isset($accessory->old_price) && $accessory->old_price > 0)
                            <span class="sale-tag">-{{(int)(100 - (100 *$accessory->price /$accessory->old_price))}}%</span>
                        @endif
                        <span class="tag">{{$accessory->matchcode}}</span> <a href="{{route('product',str_replace(' ','',str_replace('/','@',($accessory->DataSupplierArticleNumber))))}}?supplierid={{$accessory->supplierId}}" class="tittle">{{$accessory->name}}</a><br>
                        <div class="price">{{(int)$accessory->price}}грн. </div>
                        <a href="#." onclick="{{(int)$accessory->count > 0?'addCartRelate(\''.route('add_cart',$accessory->id).'\');return false;':'alert(\'Извините, данный товар отсутсвует на складе\')'}}" class="cart-btn"><i class="icon-basket-loaded"></i></a>
                    </article>
                </div>
            @endforeach
        </div>
    </section>
@endif

@if(isset($art_replace) && count($art_replace) > 0)
    <section class="padding-top-30 padding-bottom-0">
        <div class="heading">
            <h2>{{__('Товары аналоги')}}</h2>
            <hr>
        </div>
        <div class="item-slide-4 with-nav">
            @foreach($art_replace as $item)
                <div class="product">
                    <article>
                        <img class="img-responsive" src="images/item-img-1-1.jpg" alt="" >
                        @if(isset($item->old_price) && $item->old_price > 0)
                            <span class="sale-tag">-{{(int)(100 - (100 *$item->price /$item->old_price))}}%</span>
                        @endif
                        <span class="tag">{{$item->matchcode}}</span> <a href="{{route('product',str_replace('/','@',($item->articles)))}}?supplierid={{$item->supplierId}}&product_id={{$item->id}}" class="tittle">{{$item->name}}</a><br>
                        <div class="price">{{(int)$item->price}}грн. </div>
                        <a href="#." onclick="{{(int)$item->count > 0?'addCartRelate(\''.route('add_cart',$item->id).'\');return false;':'alert(\'Извините, данный товар отсутсвует на складе\')'}}" class="cart-btn"><i class="icon-basket-loaded"></i></a>
                    </article>
                </div>
            @endforeach
        </div>
    </section>
@endif
