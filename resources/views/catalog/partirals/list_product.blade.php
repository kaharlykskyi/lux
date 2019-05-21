@if(isset($list_catalog))
    <h4>{{__('Выбор производителя:')}}</h4>
    <ul class="list-group">
        @forelse($list_catalog as $item)
            <a href="{{route('catalog')}}?search_str={{$item->DataSupplierArticleNumber}}&type={{request('type')}}&supplier={{$item->supplierId}}" class="list-group-item">
                <div style="display: flex;">
                    <span style="flex-basis: 25%;"><strong>{{$item->matchcode}}</strong> - <span class="text-info">{{$item->DataSupplierArticleNumber}}</span></span>

                    <span style="flex-basis: 85%;">{{$item->NormalizedDescription}}</span>
                </div>
            </a>
        @empty
            <li class="list-group-item">
                {{__('Не найдено ничего')}}
            </li>
        @endforelse
    </ul>
@elseif(isset($list_product))
    <ul class="list-group margin-bottom-10">
        <li class="list-group-item active">
            Запрашиваемый артикл
        </li>
        @forelse($list_product as $item)
            <div class="list-group-item list-product-block">
                @foreach($item as $k => $data)

                    @if($k === 0)
                        <div style="flex-basis: 80%;display: flex">
                            <div style="flex-basis: 20%;display: flex">
                                <div>
                                    {{$data->brand}}
                                    <br><span class="text-info">{{$data->articles}}</span>
                                </div>
                            </div>
                            <div style="flex-basis: 80%;display: flex">
                                {{$data->name}}
                                <span onclick="productInfo('{{$data->articles}}' @isset($data->supplierId) ,'{{$data->supplierId}}' @endisset )" class="product-info-icon" data-toggle="modal" data-target="#productInfoModal" title="Больше инфи">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                        <div class="list-product-wrapper">
                    @endif
                            <div style="@if($k !== 0) display: none; @endif" class="list-product-item relative @if($k !== 0) prod_{{str_replace(' ','_',$data->articles)}}@endif">
                                <div style="cursor: pointer" onclick="location.href = '{{route('product',str_replace('/','@',($data->articles)))}}@isset($data->supplierId)?supplierid={{$data->supplierId}}@endisset'">
                                    <strong>
                                        {{$data->price}}грн.
                                    </strong>
                                    @if(Auth::check() && (Auth::user()->permission === 'admin' || Auth::user()->permission === 'manager'))
                                        <br><span class="small margin-bottom-3 margin-top-3"> {{$data->provider_price}} {{$data->provider_currency}}</span>
                                        <br><span class="small margin-bottom-3">кол. {{$data->count}}</span>
                                    @endif
                                </div>
                                @if($data->count > 0)
                                    <a href="#." onclick="addCart('{{route('add_cart',$data->id)}}')" class="cart-btn"><i class="icon-basket-loaded"></i></a>
                                @else
                                    <a href="#." onclick="alert('нет в наличии')" class="cart-btn"><i class="icon-basket-loaded"></i></a>
                                @endif
                            </div>
                    @if($k === count($item) - 1)
                             @if(count($item) > 1 && $k === count($item) - 1)
                                 <div class="list-product-item" style="justify-content: flex-end;">
                                     <span class="small" data-text="скрыть" onclick="moreProduct(this,'.prod_{{str_replace(' ','_',$data->articles)}}')">ещё предложения({{count($item) - 1}})</span>
                                 </div>
                             @endif
                        </div>
                    @endif
                @endforeach
            </div>
        @empty
            <a href="#" class="list-group-item">
                {{__('Не найдено ничего')}}
            </a>
        @endforelse
    </ul>
@endif

@if(isset($replace_product) && !empty($replace_product))
    <ul class="list-group margin-bottom-10">
        <li class="list-group-item active">
            Предложения по заменителям
        </li>
        @forelse($replace_product as $item)
            <div class="list-group-item list-product-block">
                @foreach($item as $k => $data)

                    @if($k === 0)
                        <div style="flex-basis: 80%;display: flex">
                            <div style="flex-basis: 20%;display: flex">
                                <div>
                                    {{$data->brand}}
                                    <br><span class="text-info">{{$data->articles}}</span>
                                </div>
                            </div>
                            <div style="flex-basis: 80%;display: flex">
                                {{$data->name}}
                                <span onclick="productInfo('{{$data->articles}}','{{$data->SupplierId}}')" class="product-info-icon" data-toggle="modal" data-target="#productInfoModal" title="Больше инфи">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                        <div class="list-product-wrapper">
                            @endif
                            <div style="@if($k !== 0) display: none; @endif" class="list-product-item relative @if($k !== 0) prod_{{$data->articles}}@endif">
                                <div style="cursor: pointer" onclick="location.href = '{{route('product',str_replace('/','@',($data->articles)))}}?supplierid={{$data->SupplierId}}'">
                                    <strong>
                                        {{$data->price}}грн.
                                    </strong>
                                    @if(Auth::check() && (Auth::user()->permission === 'admin' || Auth::user()->permission === 'manager'))
                                        <br><span class="small margin-bottom-3 margin-top-3"> {{$data->provider_price}} {{$data->provider_currency}}</span>
                                        <br><span class="small margin-bottom-3">кол. {{$data->count}}</span>
                                    @endif
                                </div>
                                @if($data->count > 0)
                                    <a href="#." onclick="addCart('{{route('add_cart',$data->id)}}')" class="cart-btn"><i class="icon-basket-loaded"></i></a>
                                @else
                                    <a href="#." onclick="alert('нет в наличии')" class="cart-btn"><i class="icon-basket-loaded"></i></a>
                                @endif
                            </div>
                            @if($k === count($item) - 1)
                                @if(count($item) > 1 && $k === count($item) - 1)
                                    <div class="list-product-item" style="justify-content: flex-end;">
                                        <span class="small" data-text="скрыть" onclick="moreProduct(this,'.prod_{{str_replace(' ','_',$data->articles)}}')">ещё предложения({{count($item) - 1}})</span>
                                    </div>
                                @endif
                        </div>
                    @endif
                @endforeach
            </div>
        @empty
            <a href="#" class="list-group-item">
                {{__('Не найдено ничего')}}
            </a>
        @endforelse
    </ul>
@endif

@include('catalog.partirals.pruduct_info_modal')

<script>
    function moreProduct(obj,id) {
        $(id).toggle();
        let text = $(obj).text();
        $(obj).text($(obj).attr('data-text')).attr('data-text',text);
    }
</script>
