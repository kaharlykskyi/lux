<div style="@if($count !== 0) display: none; @endif" class="list-product-item relative @if($count !== 0) prod_{{str_replace(' ','_',$data->articles)}}@endif">
    <div style="cursor: pointer" onclick="location.href = '{{route('product',$data->id)}}'">
        <strong>
            {{(int)$data->price}}грн.
        </strong>
        @if(Auth::check() && (Auth::user()->permission === 'admin' || Auth::user()->permission === 'manager'))
            @php
                $provider = '';
                if (isset($data->provider_name)){
                    $provider = $data->provider_name;
                } elseif (isset($data->provider)){
                    $provider = $data->provider->name;
                }
            @endphp
            <br><span class="small margin-bottom-1 margin-top-3"> {{$data->provider_price}} {{$data->provider_currency}}</span>
            <br><span class="small margin-bottom-1">кол. {{$data->count}}</span>
            <br><span class="small margin-bottom-1">постав. {{$provider}}</span>
        @endif
    </div>
    @if($data->count > 0)
        <a href="#." onclick="addCart('{{route('add_cart',$data->id)}}')" class="cart-btn">Купить</a>
    @else
        <a href="#." onclick="alert('нет в наличии')" class="cart-btn">Купить</a>
    @endif
</div>
