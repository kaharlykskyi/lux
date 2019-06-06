@if(isset($list_catalog))
    <h4>{{__('Выбор производителя:')}}</h4>
    <ul class="list-group">
        @forelse($list_catalog as $item)
            <a href="{{route('catalog')}}?search_str={{$item->articles}}&type={{request('type')}}&supplier={{$item->supplierId}}" class="list-group-item">
                <div style="display: flex;">
                    <span style="flex-basis: 25%;"><strong>{{$item->brand}}</strong> - <span class="text-info">{{$item->articles}}</span></span>

                    <span style="flex-basis: 85%;">{{$item->name}}</span>
                </div>
            </a>
        @empty
            <li class="list-group-item">
                {{__('По артикулу "' . request('search_str') . '" товара в наличии, но можете просмотреть предложение по заменителям')}}
            </li>
        @endforelse
    </ul>
@elseif(isset($list_product))
    <ul class="list-group margin-bottom-10">
        <li class="list-group-item active">
            Запрашиваемый артикл - {{request('search_str')}}
        </li>
        @forelse($list_product as $item)
            <div class="list-group-item list-product-block">
                @php
                    $count = 0;
                @endphp
                @foreach($item as $k => $data)

                    @if($k === 0)
                        <div style="flex-basis: 80%;display: flex">
                            <div style="flex-basis: 20%;display: flex">
                                <div>
                                    @if(!empty($item->file))
                                        @php $brand_folder = explode('_',$item->file) @endphp
                                        <img style="margin: 10px 0;width: 100px;" class="img-responsive" src="{{asset('product_imags/'.$brand_folder[0].'/'.str_ireplace(['.BMP','.JPG'],'.jpg',$item->file))}}" alt="" >
                                    @else
                                        <img style="margin: 10px 0;width: 100px;" class="img-responsive" src="{{asset('images/default-no-image_2.png')}}" alt="" >
                                    @endif
                                    {{$data->brand}}
                                    <br><span class="text-info">{{$data->articles}}</span>
                                </div>
                            </div>
                            <div style="flex-basis: 80%;display: flex">
                                {{$data->name}}
                                <span onclick="productInfo('{{$data->articles}}' @isset($data->SupplierId) ,'{{$data->SupplierId}}' @endisset )" class="product-info-icon" data-toggle="modal" data-target="#productInfoModal" title="Больше инфи">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                        <div class="list-product-wrapper">
                    @endif
                        @if(count($item) > 1)
                            @if(Auth::check() && (Auth::user()->permission === 'admin' || Auth::user()->permission === 'manager'))
                                @include('catalog.partirals.product_item_list')
                                @php
                                    $count += 1;
                                @endphp
                            @else
                                @if($count === 0 && $data->count > 0)
                                    @include('catalog.partirals.product_item_list')
                                    @php
                                        $count += 1;
                                    @endphp
                                @elseif($count === 0 && $k === count($item) - 1)
                                    @include('catalog.partirals.product_item_list')
                                @endif
                            @endif
                        @else
                             @include('catalog.partirals.product_item_list')
                        @endif
                    @if($k === count($item) - 1)
                             @if(count($item) > 1 && $k === count($item) - 1 && Auth::check() && (Auth::user()->permission === 'admin' || Auth::user()->permission === 'manager'))
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
            @php
                $count = 0;
            @endphp
            <div class="list-group-item list-product-block">
                @foreach($item as $k => $data)

                    @if($k === 0)
                        <div style="flex-basis: 80%;display: flex">
                            <div style="flex-basis: 20%;display: flex">
                                <div>
                                    @if(!empty($data->file))
                                        @php $brand_folder = explode('_',$data->file) @endphp
                                        <img style="margin: 10px 0;width: 100px;" class="img-responsive" src="{{asset('product_imags/'.$brand_folder[0].'/'.str_ireplace(['.BMP','.JPG'],'.jpg',$data->file))}}" alt="" >
                                    @else
                                        <img style="margin: 10px 0;width: 100px;" class="img-responsive" src="{{asset('images/default-no-image_2.png')}}" alt="" >
                                    @endif
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

                            @if(count($item) > 1)
                                @if(Auth::check() && (Auth::user()->permission === 'admin' || Auth::user()->permission === 'manager'))
                                    @include('catalog.partirals.product_item_list')
                                    @php
                                        $count += 1;
                                    @endphp
                                @else
                                    @if($count === 0 && (int)$data->count > 0)
                                        @include('catalog.partirals.product_item_list')
                                        @php
                                            $count += 1;
                                        @endphp
                                    @elseif($count === 0 && $k === count($item) - 1)
                                        @include('catalog.partirals.product_item_list')
                                    @endif
                                @endif
                            @else
                                @include('catalog.partirals.product_item_list')
                            @endif

                            @if($k === count($item) - 1)
                                @if(count($item) > 1 && $k === count($item) - 1 && Auth::check() && (Auth::user()->permission === 'admin' || Auth::user()->permission === 'manager'))
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
