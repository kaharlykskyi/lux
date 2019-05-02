<div class="shop-side-bar" style="max-height: 1010px;overflow: auto;">
    @if(session()->has('filter'))
        <a onclick="clearFilter(this);return false;" class="btn btn-default close-filter" href="" role="button" title="{{__('очистить фильтр')}}">
            <i class="fa fa-times" aria-hidden="true"></i>
        </a>
    @endif

    <h6>{{__('Цена')}}</h6>
    <!-- PRICE -->
    <div class="cost-price-content">
        <div id="price-range" class="price-range"></div>
        <span id="price-min" class="price-min">20</span>
        <span id="price-max" class="price-max">80</span>
        <a href="#." onclick="setPrice()" class="btn-round" >{{__('Фильтровать')}}</a>
    </div>

    <!-- Featured Brands -->
    <h6>{{__('Производители')}}</h6>
    <div class="checkbox checkbox-primary" style="max-height: 155px;overflow: auto;">
        <ul>
            @isset($brands)
                @foreach($brands as $brand)
                    <li>
                        <input @if(in_array($brand->supplierId,$filter_supplier)) checked @endif onchange="setSupplier(this)" id="brand{{$brand->supplierId}}" value="{{$brand->supplierId}}" class="styled" type="checkbox" >
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
        @foreach($attributes as $attr)
            @isset($attr['filter_item'])
                @php $request_attr = request()->has($attr['hurl'])?explode(',',request($attr['hurl'])):[] @endphp
                <h6>{{$attr['description']}}</h6>
                <div class="checkbox checkbox-primary" style="max-height: 155px;overflow: auto;">
                    <ul>
                        @foreach($attr['filter_item'] as $k => $item)
                            <li>
                                <input @if(in_array($item->displayvalue,$request_attr)) checked @endif onchange="setAttrFilter(this)" data-hurl="{{$attr['hurl']}}" value="{{$item->displayvalue}}" id="cate_{{$item->id .'_'. $k}}" class="styled" type="checkbox" >
                                <label for="cate_{{$item->id .'_'. $k}}">{{$item->displayvalue}}</label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endisset
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
        const url = window.location.href;
        const data_checkbox = $(obj);
        let parser = document.createElement('a');
        parser.href = url;

        let search_str = parser.search.substring(1, parser.search.length).split('&');
        let use_supplier  = true;
        search_str.forEach(function (item, i, search_str) {
            let data = item.split('=');
            if (data[0] === 'supplier') {
                let supplier_id = data[1].split(',');
                supplier_id.forEach(function (val,key,supplier_id) {
                    if (data_checkbox[0].checked && $.inArray(data_checkbox[0].value,supplier_id) === -1){
                        supplier_id.push(data_checkbox[0].value);
                    }else {
                        if(data_checkbox[0].value === val){
                            supplier_id.splice(key,1);
                        }
                    }
                });
                search_str[i] = `supplier=${supplier_id.join(',')}`;
                use_supplier = false;
            }
        });

        if (use_supplier){
            search_str.push(`supplier=${data_checkbox[0].value}`);
        }

        location.href = `${parser.protocol}//${parser.host}${parser.pathname}?${search_str.join('&')}`;
    }

    function setAttrFilter(obj) {
        const url = window.location.href;
        let parser = document.createElement('a');
        parser.href = url;
        const data_checkbox = $(obj);

        let search_str = parser.search.substring(1, parser.search.length).split('&');
        let use_attr  = true;

        search_str.forEach(function (item, i, search_str) {
            let data = item.split('=');
            const hurl = $(obj).attr('data-hurl');
            if (data[0] === hurl) {
                let attr_item = data[1].split(',');
                attr_item.forEach(function (val,key,attr_item) {
                    if (data_checkbox[0].checked && $.inArray(data_checkbox[0].value,attr_item) === -1){
                        attr_item.push(data_checkbox[0].value);
                    }else {
                        if(data_checkbox[0].value === val){
                            attr_item.splice(key,1);
                        }
                    }
                });
                search_str[i] = `${$(obj).attr('data-hurl')}=${attr_item.join(',')}`;
                use_attr = false;
            }
        });

        if (use_attr){
            search_str.push(`${$(obj).attr('data-hurl')}=${data_checkbox[0].value}`);
        }

        /*parser.protocol; // => "http:"
        parser.pathname; // => "/pathname/"
        parser.search;   // => "?search=test"
        parser.host; // => "example.com:3000"*/

        location.href = `${parser.protocol}//${parser.host}${parser.pathname}?${search_str.join('&')}`;
    }

</script>
