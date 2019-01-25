<div class="shop-side-bar" style="max-height: 1010px;overflow: auto;">
    @if(session()->has('filter'))
        <a onclick="clearFilter(this)" class="btn btn-default close-filter" href="#" role="button" title="{{__('очистить фильтр')}}">
            <i class="fa fa-times" aria-hidden="true"></i>
        </a>
    @endif
    @if((int)$min_price > 0)
    <h6>Price</h6>
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
                'min': [ {{(float)$min_price}} ],
                'max': [ {{(float)$max_price}} ]
            },
            start: [ {{session()->has('filter.min_price')?session('filter.min_price'):(float)$min_price}} , {{session()->has('filter.max_price')?session('filter.max_price'):(float)$max_price}}],
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
        $.get(`{{route('filter')}}?min_price=${$('#price-min').text()}&max_price=${$('#price-max').text()}`,()=>{location.reload()});
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