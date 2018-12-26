<div class="shop-side-bar">

    <!-- Categories -->
    <h6>Categories</h6>
    <div class="checkbox checkbox-primary">
        <ul>
            <li>
                <input id="cate1" class="styled" type="checkbox" >
                <label for="cate1"> Home Audio & Theater </label>
            </li>
        </ul>
    </div>

    <!-- Categories -->
    <h6>Price</h6>
    <!-- PRICE -->
    <div class="cost-price-content">
        <div id="price-range" class="price-range"></div>
        <span id="price-min" class="price-min">20</span> <span id="price-max" class="price-max">80</span> <a href="#." class="btn-round" >Filter</a> </div>

    <!-- Featured Brands -->
    <h6>Featured Brands</h6>
    <div class="checkbox checkbox-primary">
        <ul>
            @isset($brands)
                @foreach($brands as $brand)
                    <li>
                        <input id="brand{{$brand->id}}" class="styled" type="checkbox" >
                        <label for="brand{{$brand->id}}">
                            {{$brand->description}}
                            <span>({{DB::table('products')->where('brand',$brand->description)->count()}})</span>
                        </label>
                    </li>
                @endforeach
            @endisset
        </ul>
    </div>

    <!-- Colors -->
    <h6>Size</h6>
    <div class="rating">
        <ul>
            <li><a href="#."><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i> <span>(218)</span></a></li>
            <li><a href="#."><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i> <span>(178)</span></a></li>
            <li><a href="#."><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i> <span>(79)</span></a></li>
            <li><a href="#."><i class="fa fa-star"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i><i class="fa fa-star-o"></i> <span>(188)</span></a></li>
        </ul>
    </div>
</div>
<script>
    jQuery(document).ready(function($) {

        //  Price Filter ( noUiSlider Plugin)
        $("#price-range").noUiSlider({
            range: {
                'min': [ 0 ],
                'max': [ 1000 ]
            },
            start: [40, 940],
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
                    prefix: '$'
                }
            }
        })
    })

</script>