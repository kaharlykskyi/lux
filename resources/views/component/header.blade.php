<header>
    <div class="container">
        <div class="logo">
            <a href="{{ route('home') }}"><img src="{{asset('images/logo.png')}}" alt="{{ config('app.name') }}" ></a>
        </div>
        <div class="search-cate">
            <select class="selectpicker">
                <option> All Categories</option>
            </select>
            <input type="search" placeholder="Search entire store here...">
            <button class="submit" type="submit"><i class="icon-magnifier"></i></button>
        </div>

        <!-- Cart Part -->
        <ul class="nav navbar-right cart-pop">
            <li>
                    @php
                        $cart = DB::table('carts')->where([
                                isset(Auth::user()->id)
                                    ?['user_id',Auth::user()->id]
                                    :['session_id',Cookie::get('cart_session_id')],
                                ['oder_status', 1]
                            ])->first();
                        if (isset($cart)){
                            $products = DB::table('cart_products')
                                ->where('cart_products.cart_id',$cart->id)
                                ->join('products','products.id','=','cart_products.product_id')
                                ->select('products.*','cart_products.count')
                                ->get();
                        }
                @endphp
                <a href="#" onclick="getCartItem('{{route('cart')}}'); return false;" data-toggle="modal" data-target="#cart">
                    <span class="itm-cont">@if(isset($products)){{count($products )}}@else{{__('0')}}@endif</span>
                    <i class="flaticon-shopping-bag"></i>
                    <strong>{{__('Корзина')}}</strong> <br>
                    <span id="total-price">
                        @php
                            $sum = 0.00;
                            if (isset($products)){
                                foreach ($products as $product){
                                    $sum += (double)$product->price * (integer)$product->count;
                                }
                            }
                        @endphp
                        {{$sum}} грн
                    </span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Nav -->
    <nav class="navbar ownmenu">
        <div class="container">
            <!-- Categories -->
            <div class="cate-lst"> <a  data-toggle="collapse" class="cate-style" href="#cater"><i class="fa fa-list-ul"></i> Our Categories </a>
                <div class="cate-bar-in">
                    <div id="cater" class="collapse">
                        <ul class="category-list">
                            <li><a href="{{route('catalog')}}">{{__('Все Категории')}}</a></li>
                            @isset($category)
                                @foreach($category as $k => $item)
                                    @if($k < 15)
                                        <li class="sub-menu"><a id="sub-category-link{{$k}}" onmouseenter="getSub('{{$item->assemblygroupdescription}}','{{$k}}')" href="#.">{{$item->assemblygroupdescription}}</a>
                                            <ul id="sub-category{{$k}}">
                                                <li style="text-align: center;">
                                                    <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                                    <span class="sr-only">Loading...</span>
                                                </li>
                                            </ul>
                                        </li>
                                    @endif
                                    @if($k === 15)
                                        <li id="more-category"><span class="h6 padding-5" onclick="$('#more-category').hide();$('li.sub-menu.hidden').removeClass('hidden');">{{__('больше категорий...')}}</span></li>
                                    @endif
                                    @if($k >= 15)
                                        <li class="sub-menu hidden"><a id="sub-category-link{{$k}}" onmouseenter="getSub('{{$item->assemblygroupdescription}}','{{$k}}')" href="#.">{{$item->assemblygroupdescription}}</a>
                                            <ul id="sub-category{{$k}}">
                                                <li style="text-align: center;">
                                                    <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                                    <span class="sr-only">Loading...</span>
                                                </li>
                                            </ul>
                                        </li>
                                    @endif
                                @endforeach
                                <script>
                                    function getSub(data,id) {
                                        $.get('{{route('get_subcategory')}}?category=' + data,function (data) {
                                            let tada_str = '';
                                            for (let i = 0;i < data.subCategory.length; i++){
                                                if(data.subCategory[i].usagedescription !== ''){
                                                    tada_str += `<li><a href="{{route('catalog')}}/${urlRusLat(data.subCategory[i].usagedescription)}">${data.subCategory[i].usagedescription}</a></li>`;
                                                }
                                            }
                                            $('#sub-category'+id).html(tada_str);
                                            $('#sub-category-link'+id).attr('onmouseenter','');
                                        });
                                    }
                                </script>
                            @endisset
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Navbar Header -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav-open-btn" aria-expanded="false"> <span><i class="fa fa-navicon"></i></span> </button>
            </div>
            <!-- NAV -->
            <div class="collapse navbar-collapse" id="nav-open-btn">
                <ul class="nav">
                    <li class="dropdown"> <a href="index.html" class="dropdown-toggle" data-toggle="dropdown">{{__('Информация')}}</a>
                        <ul class="dropdown-menu multi-level animated-2s fadeInUpHalf">
                            @isset($pages)
                                @foreach($pages as $page)
                                    <li><a href="{{route('page',$page->alias)}}">{{$page->title}}</a></li>
                                @endforeach
                            @endisset
                        </ul>
                    </li>
                    <!-- Mega Menu Nav -->
                    <li class="dropdown megamenu"> <a href="index.html" class="dropdown-toggle" data-toggle="dropdown">Mega menu </a>
                        <div class="dropdown-menu animated-2s fadeInUpHalf">
                            <div class="mega-inside">
                                <div class="top-lins">
                                    <ul>
                                        <li><a href="#."> Cell Phones & Accessories </a></li>
                                        <li><a href="#."> Carrier Phones </a></li>
                                    </ul>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <h6>Electronics</h6>
                                        <ul>
                                            <li><a href="#."> Cell Phones & Accessories </a></li>
                                            <li><a href="#."> Carrier Phones </a></li>
                                            <li><a href="#."> All Electronics </a></li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-3">
                                        <h6>Computers</h6>
                                        <ul>
                                            <li><a href="#."> Computers & Tablets</a></li>
                                            <li><a href="#."> Monitors</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-2">
                                        <h6>Home Appliances</h6>
                                        <ul>
                                            <li><a href="#."> Refrigerators</a></li>
                                            <li><a href="#."> Wall Ovens</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-4"> <img class=" nav-img" src="images/navi-img.png" alt="" > </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown"> <a href="blog.html" class="dropdown-toggle" data-toggle="dropdown">Blog</a>
                        <ul class="dropdown-menu multi-level animated-2s fadeInUpHalf">
                            <li><a href="Blog.html">Blog </a></li>
                            <li><a href="Blog_details.html">Blog Single </a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- NAV RIGHT -->
            <div class="nav-right"> <span class="call-mun"><i class="fa fa-phone"></i> <strong>Hotline:</strong> (+100) 123 456 7890</span> </div>
        </div>
    </nav>
</header>