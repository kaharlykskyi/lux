<header>
    <div class="container">
        <div class="logo">
            <a href="{{ route('home') }}"><img src="{{asset('images/logo.png')}}" alt="{{ config('app.name') }}" ></a>
        </div>
        <div class="search-cate">
            <form action="{{route('vin_decode')}}" method="post" id="search_global_form">
                @csrf
                <select class="selectpicker" name="type_search_global">
                    <option selected value="articles">{{__('По артикулу')}}</option>
                    <option value="name">{{__('По названию')}}</option>
                    <option value="vin">{{__('По vin')}}</option>
                </select>
                <input type="search" name="vin" placeholder="Поиск">
                <button class="submit" type="submit"><i class="icon-magnifier"></i></button>
            </form>
            <script>
                $(document).ready(function () {
                    $('#search_global_form').submit(function (e) {
                        if ($(this).find('select').val() !== 'vin'){
                            e.preventDefault();
                            const search = encodeURIComponent($(this).find('input[type="search"]').val());
                            if (search.length > 1){
                                location.href = `{{route('catalog',['category' => null])}}?search_str=${search}&type=${$(this).find('select').val()}`;
                            } else{
                                alert('Длина стоки должна быть больше 1');
                            }
                        }
                    });
                });
            </script>
        </div>

        <!-- Cart Part -->
        <ul class="nav navbar-right cart-pop">
            <li>
                <a href="#" onclick="getCartItem('{{route('cart')}}'); return false;" data-toggle="modal" data-target="#cart">
                    <span id="count-product-mini-cart" class="itm-cont">@if(isset($products_cart_global)){{count($products_cart_global )}}@else{{__('0')}}@endif</span>
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    <strong>{{__('Корзина')}}</strong> <br>
                    <span id="total-price">
                        @php
                            $sum = 0.00;
                            if (isset($products_cart_global)){
                                foreach ($products_cart_global as $product){
                                    $sum += (double)$product->price * (integer)$product->count;
                                }
                            }
                        @endphp
                        {{(int)$sum}} грн
                    </span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Nav -->
    <nav class="navbar ownmenu">
        <div class="container">
            <div class="cate-lst visible-xs" style="height: 55px;"></div>

            <!-- Navbar Header -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav-open-btn" aria-expanded="false"> <span><i class="fa fa-navicon"></i></span> </button>
            </div>

            <div class="collapse navbar-collapse" id="nav-open-btn">
                <ul class="nav">
                    @isset($all_category_global)
                        @foreach($all_category_global as $category)
                            <li class="dropdown megamenu">
                                <a href="./#" class="dropdown-toggle" data-toggle="dropdown">{{$category->title}}</a>
                                <div class="dropdown-menu animated-2s fadeInUpHalf">
                                    <div class="mega-inside">
                                        <div class="row">
                                            <div class="col-sm-9">
                                                @foreach($category->childCategories as $child)
                                                    <div class="col-sm-3">
                                                        <h6>{{$child->title}}</h6>
                                                        <ul>
                                                            @isset($child->sub_categores)
                                                                @foreach($child->sub_categores as $item)
                                                                    @if(isset($search_cars[0]))
                                                                        <li><a href="{{route('catalog',$item->tecdoc_id)}}?car={{$search_cars[0]['cookie']['modification_auto']}}"> {{$item->name}} </a></li>
                                                                    @else
                                                                        <li><a href="{{route('rubric.choose_car',$item->tecdoc_id)}}"> {{$item->name}} </a></li>
                                                                    @endif
                                                                @endforeach
                                                            @endisset
                                                        </ul>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="col-sm-3"> <img class="" src="{{asset('images/catalog/' . $category->logo)}}" alt="" > </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @endisset
                </ul>
            </div>
        </div>
    </nav>
</header>
