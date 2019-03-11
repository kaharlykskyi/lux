<div class="shop-side-bar" style="max-height: 1010px;overflow: auto;">
    @if(session()->has('filter'))
        <a onclick="clearFilter(this);return false;" class="btn btn-default close-filter" href="" role="button" title="{{__('очистить фильтр')}}">
            <i class="fa fa-times" aria-hidden="true"></i>
        </a>
    @endif
    @if($min_price->start_price > 0)
    <h6>{{__('Цена')}}</h6>
    <!-- PRICE -->
    <div class="cost-price-content">
        <div id="price-range" class="price-range"></div>
        <span id="price-min" class="price-min">20</span>
        <span id="price-max" class="price-max">80</span>
        <a href="#." onclick="setPrice()" class="btn-round" >{{__('Фильтровать')}}</a>
    </div>
    @endif
    <!-- Featured Brands -->
    <h6>{{__('Производители')}}</h6>
    <div class="checkbox checkbox-primary" style="max-height: 155px;overflow: auto;">
        <ul>
            @isset($brands)
                @foreach($brands as $brand)
                    @php $filter_brand = session("filter.suppliers.{$brand->supplierId}"); @endphp
                    <li>
                        <input @isset($filter_brand) checked @endisset onchange="setSupplier(this)" id="brand{{$brand->supplierId}}" value="{{$brand->supplierId}}" class="styled" type="checkbox" >
                        <label for="brand{{$brand->supplierId}}">
                            {{$brand->description}}
                        </label>
                    </li>
                @endforeach
            @endisset
        </ul>
    </div>

    <!--Attribute-->
    @isset($attributes)
        @foreach($attributes as $attribute)
            @php $buff = []; @endphp
            @foreach($attribute as $k => $item)
                @if($k === 0)
                    <h6>{{$item->description}}</h6>
                    <div class="checkbox checkbox-primary" style="max-height: 155px;overflow: auto;">
                        <ul>
                @endif
                @if(!in_array($item->displayvalue,$buff))
                     @php
                         $filter_attr_data = session("filter.attributes.{$item->id}");
                         $filter_attr = null;
                         if (isset($filter_attr_data)){
                            foreach ($filter_attr_data as $val){
                                if ($val === $item->displayvalue){
                                    $filter_attr = true;
                                }
                            }
                         }
                     @endphp
                            <li>
                                <input @isset($filter_attr) checked @endisset onchange="setAttrFilter(this)" value="{{__("{$item->id}@{$item->displayvalue}")}}" id="cate_{{$item->id .'_'. $k}}" class="styled" type="checkbox" >
                                <label for="cate_{{$item->id .'_'. $k}}">{{$item->displayvalue}}</label>
                            </li>
                    @php array_push($buff,$item->displayvalue); @endphp
                @endif
                @if($k + 1 === count($attribute))
                        </ul>
                    </div>
                @endif
            @endforeach
        @endforeach
    @endisset

</div>
<script>
    jQuery(document).ready(function($) {

        //  Price Filter ( noUiSlider Plugin)
        $("#price-range").noUiSlider({
            range: {
                'min': [ {{$min_price->start_price}} ],
                'max': [ {{$max_price->start_price}} ]
            },
            start: [
                {{($min_price->filter_price > 0)?$min_price->filter_price:$min_price->start_price}} ,
                {{($max_price->filter_price > 0)?$max_price->filter_price:$max_price->start_price}}
            ],
            connect:true,
            serialization:{
                lower: [
                    $.Link({
                        target: $("#price-min")
                    })
                ],
                upper: [
                    $.Link({
                        target: $("#price-max")
                    })
                ],
                format: {
                    // Set formatting
                    decimals: 2,
                    prefix: '₴'
                }
            }
        })
    });

    function setPrice() {
        const url = window.location.href;
        let parser = document.createElement('a');
        parser.href = url;

        parser.protocol; // => "http:"
        parser.pathname; // => "/pathname/"
        parser.search;   // => "?search=test"
        parser.host; // => "example.com:3000"

        let search_str = parser.search.substring(1, parser.search.length).split('&');
        let max = true;
        let min = true;
        search_str.forEach(function (item, i, search_str) {
            let data = item.split('=');
            if (data[0] === 'min') {
                search_str[i] = `min=${$('#price-min').text().substring(1, $('#price-min').text().length)}`;
                min = false;
            }
            if (data[0] === 'max') {
                search_str[i] = `max=${$('#price-max').text().substring(1, $('#price-max').text().length)}`;
                max = false;
            }

        });

        if (max) {
            search_str.push(`max=${$('#price-max').text().substring(1, $('#price-max').text().length)}`);
        }

        if (min) {
            search_str.push(`min=${$('#price-min').text().substring(1, $('#price-min').text().length)}`);
        }

        location.href = `${parser.protocol}//${parser.host}${parser.pathname}?${search_str.join('&')}`;
    }

    function clearFilter(obj) {
        $(obj).html('<i class="fa-li fa fa-spinner fa-spin">');
        $.get(`{{route('filter')}}?clear_filter=true`,()=>{location.reload()});
    }

    function setSupplier(obj) {
        const data = $(obj);
        $.get(`{{route('filter')}}?supplierid=${data[0].value}&active=${data[0].checked}`,()=>{location.reload()});
    }

    function setAttrFilter(obj) {
        const data = $(obj);
        $.get(`{{route('filter')}}?attrFilter=${data[0].value}&active=${data[0].checked}`,()=>{location.reload()});
    }

</script>