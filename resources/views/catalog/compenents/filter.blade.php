<div class="shop-side-bar">

    @if((int)$min_price > 0)
    <h6>Price</h6>
    <!-- PRICE -->
    <div class="cost-price-content">
        <div id="price-range" class="price-range"></div>
        <span id="price-min" class="price-min">20</span> <span id="price-max" class="price-max">80</span> <a href="#." class="btn-round" >{{__('Фильтровать')}}</a>
    </div>
    @endif
    <!-- Featured Brands -->
    <h6>{{__('Производители')}}</h6>
    <div class="checkbox checkbox-primary" style="max-height: 155px;overflow: auto;">
        <ul>
            @isset($brands)
                @foreach($brands as $brand)
                    <li>
                        <input id="brand{{$brand->supplierId}}" class="styled" type="checkbox" >
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
                            <li>
                                <input value="{{$item->id}}" id="cate_{{$item->id .'_'. $k}}" class="styled" type="checkbox" >
                                <label for="cate1">{{$item->displayvalue}}</label>
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
                'min': [ {{(int)$min_price}} ],
                'max': [ {{(int)$max_price}} ]
            },
            start: [ {{(int)$min_price}} , {{(int)$max_price}}],
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
    })

</script>