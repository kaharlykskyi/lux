@if(isset($list_catalog))
    <h4>{{__('Выбор производителя:')}}</h4>
    <ul class="list-group">
        @forelse($list_catalog as $item)
            @isset($item->NormalizedDescription)
                <a style="display: flex;" href="{{route('catalog')}}?search_str={{request('search_str')}}&type={{request('type')}}&supplier={{$item->SupplierId}}" class="list-group-item">
                    <strong style="margin-right: 20px;flex-basis: 15%;">{{$item->matchcode}}</strong>
                    <span style="flex-basis: 85%;" class="small">
                        {{$item->NormalizedDescription}}
                    </span>
                </a>
            @endisset
        @empty
            <li class="list-group-item">
                {{__('Не найдено ничего')}}
            </li>
        @endforelse
    </ul>
@elseif(isset($list_product))
    @php
        dump($list_product);
    @endphp
    <div class="list-group">
        @forelse($list_product as $item)
            @foreach($item as $k => $data)
                <div class="list-group-item" style="display: flex;border: none;border-top: 1px solid #ddd;">
                    @if($k === 0)
                        <div style="flex-basis: 80%;display: flex">
                            <div style="flex-basis: 20%;display: flex">
                                {{$data->brand}}
                            </div>
                            <div style="flex-basis: 80%;display: flex">
                                {{$data->name}}
                            </div>
                        </div>
                    @endif

                    <div style="flex-basis: 20%;">
                        <div style="@if($k !== 0) display: none; @endif" class="@if($k !== 0) prod_{{$data->articles}}@endif">
                            <strong>{{$data->price}}грн.</strong>
                            @if($data->count > 0)
                                <a href="#." onclick="addCart('{{route('add_cart',$data->id)}}')" class="cart-btn"><i class="icon-basket-loaded"></i></a>
                            @else
                                <a href="#." onclick="alert('нет в наличии')" class="cart-btn"><i class="icon-basket-loaded"></i></a>
                            @endif
                        </div>
                    </div>

                    @if(count($item) > 1 && $k === count($item) - 1)
                        <span data-text="скрыть" onclick="moreProduct(this,'.prod_{{$data->articles}}')">ещё предложения({{count($item) - 1}})</span>
                    @endif
                </div>
            @endforeach
        @empty
            <a href="#" class="list-group-item active">
                {{__('Не найдено ничего')}}
            </a>
        @endforelse
    </div>
@endif

<script>
    function moreProduct(obj,id) {
        $(id).toggle();
        let text = $(obj).text();
        $(obj).text($(obj).attr('data-text')).attr('data-text',text);
    }
</script>
