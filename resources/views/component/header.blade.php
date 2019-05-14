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
                    <span class="itm-cont">@if(isset($products_cart_global)){{count($products_cart_global )}}@else{{__('0')}}@endif</span>
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
            <div class="cate-lst"> <a  data-toggle="collapse" class="cate-style" href="#cater"><i class="fa fa-list-ul"></i> {{__('Каталог')}} </a>
                <div class="cate-bar-in">
                    <div id="cater" class="collapse">
                        <ul class="list-group root-list">
                            <li class="list-group-item">
                                <a class="root-link" onclick="getSub('passenger',null,this,'{{route('get_subcategory')}}')" href="#.">{{__('Легковой')}}</a>
                                <ul class="list-group" style="display: none">
                                    <li style="text-align: center;">
                                        <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                        <span class="sr-only">Loading...</span>
                                    </li>
                                </ul>
                            </li>
                            <li  class="list-group-item">
                                <a class="root-link" onclick="getSub('commercial',null,this,'{{route('get_subcategory')}}')" href="#.">{{__('Грузовой')}}</a>
                                <ul class="list-group" style="display: none">
                                    <li style="text-align: center;">
                                        <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                        <span class="sr-only">Loading...</span>
                                    </li>
                                </ul>
                            </li>
                        </ul>

                        <script>
                            $(document).ready(function () {
                                $('#cater').click(function (e) {
                                    if (e.target.nodeName === 'A' && e.target.className === 'root-link'){
                                        $($(e.target).siblings("ul")).toggle();
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>

            <!-- Navbar Header -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav-open-btn" aria-expanded="false"> <span><i class="fa fa-navicon"></i></span> </button>
            </div>

            <div class="collapse navbar-collapse" id="nav-open-btn">
                <ul class="nav">
                    @php
                        $top_menu = \App\TopMenu::where('show_menu','1')->get();
                    @endphp
                    @isset($top_menu)
                        @foreach($top_menu as $category)
                            @php
                                $sub = DB::connection('mysql_tecdoc')
                                    ->table('passanger_car_prd')
                                    ->where('assemblygroupdescription',$category->tecdoc_title)
                                    ->select('id','description')
                                    ->distinct()
                                    ->limit(10)
                                    ->get();
                            @endphp
                            <li @isset($sub)class="dropdown"@endisset>
                                <a @isset($sub)class="dropdown-toggle" data-toggle="dropdown" @endisset href="{{route('rubric',$category->tecdoc_title)}}">{{$category->title}} </a>
                                @isset($sub)
                                    <ul class="dropdown-menu multi-level animated-2s fadeInUpHalf">
                                        @foreach($sub as $item)
                                            <li><a href="{{route('catalog',$item->id)}}"> {{$item->description}} </a></li>
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
