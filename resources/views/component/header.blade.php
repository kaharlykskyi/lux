<header>
    <div class="container">
        <div class="logo"> <a href="{{ route('home') }}"><img src="{{asset('images/logo.png')}}" alt="{{ config('app.name', 'Laravel') }}" ></a> </div>
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
                @isset(Auth::user()->id)
                    @php
                        $cart = DB::table('carts')->where([['user_id', Auth::user()->id],['oder_status', 1]])->first();
                        if (isset($cart)){
                            $products = DB::table('cart_products')
                                ->where('cart_products.cart_id',$cart->id)
                                ->join('products','products.id','=','cart_products.product_id')
                                ->select('products.*','cart_products.count')
                                ->get();
                        }
                    @endphp
                @endisset
                <a href="#" @guest @else data-toggle="modal" data-target="#cart" onclick="getCartItem('{{route('cart')}}'); return false;" @endguest >
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
                        <ul>
                            <li><a href="#."> Home Audio & Theater</a></li>
                            <li><a href="#."> TV & Video</a></li>
                            <li><a href="#."> Camera, Photo & Video</a></li>
                            <li class="sub-menu"><a href="#."> Cell Phones & Accessories</a>
                                <ul>
                                    <li><a href="#."> TV & Video</a></li>
                                    <li><a href="#."> Camera, Photo & Video</a></li>
                                    <li><a href="#."> Cell Phones & Accessories</a>
                                </ul>
                            </li>
                            <li><a href="#."> Headphones</a></li>
                            <li><a href="#."> Video Games</a></li>
                            <li class="sub-menu"><a href="#."> Bluetooth & Wireless Speakers</a>
                                <ul>
                                    <li><a href="#."> TV & Video</a></li>
                                    <li><a href="#."> Camera, Photo & Video</a></li>
                                    <li><a href="#."> Cell Phones & Accessories</a>
                                </ul>
                            </li>
                            <li class="sub-menu"><a href="#."> Gaming Console</a>
                                <ul>
                                    <li><a href="#."> TV & Video</a></li>
                                    <li><a href="#."> Camera, Photo & Video</a></li>
                                    <li><a href="#."> Cell Phones & Accessories</a>
                                </ul>
                            </li>
                            <li><a href="#."> Computers & Tablets</a></li>
                            <li><a href="#."> Monitors</a></li>
                            <li><a href="#."> Home Appliances</a></li>
                            <li><a href="#."> Office Supplies</a></li>
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
                    <li class="dropdown megamenu active"> <a href="index.html" class="dropdown-toggle" data-toggle="dropdown">Home </a>
                        <div class="dropdown-menu animated-2s fadeInUpHalf">
                            <div class="mega-inside scrn">
                                <ul class="home-links">
                                    <li><a href="index.html"><img class="img-responsive" src="images/home-1.jpg" alt="" > <span>Home Version 1</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown"> <a href="index.html" class="dropdown-toggle" data-toggle="dropdown">Pages </a>
                        <ul class="dropdown-menu multi-level animated-2s fadeInUpHalf">
                            <li><a href="About.html"> About </a></li>
                            <li class="dropdown-submenu"><a href="#."> Dropdown Level </a>
                                <ul class="dropdown-menu animated-2s fadeInRight">
                                    <li><a href="#.">Level 1</a></li>
                                </ul>
                            </li>
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
                    <li> <a href="shop.html">Buy theme! </a></li>
                </ul>
            </div>

            <!-- NAV RIGHT -->
            <div class="nav-right"> <span class="call-mun"><i class="fa fa-phone"></i> <strong>Hotline:</strong> (+100) 123 456 7890</span> </div>
        </div>
    </nav>
</header>