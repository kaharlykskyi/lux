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
                    @isset($top_menu_global)
                        @foreach($top_menu_global as $category)
                            @php
                                $sub = json_decode($category->tecdoc_category)
                            @endphp
                            <li @isset($sub)class="dropdown"@endisset>
                                <a class="dropdown-toggle" data-toggle="dropdown" href=".#">{{$category->title}} </a>
                                @isset($sub)
                                    <ul class="dropdown-menu multi-level animated-2s fadeInUpHalf">
                                        @foreach($sub as $item)
                                            <li><a href="{{route('catalog',$item->id)}}"> {{$item->name}} </a></li>
                                        @endforeach
                                    </ul>
                                @endisset
                            </li>
                        @endforeach
                    @endisset
                </ul>
            </div>
        </div>
    </nav>
</header>
