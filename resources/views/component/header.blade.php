<header>
    <div class="container">
        <div class="logo">
            <a href="{{ route('home') }}"><img src="{{asset('images/logo.png')}}" alt="{{ config('app.name') }}" ></a>
        </div>
        <div class="search-cate">
            <form action="{{route('vin_decode')}}" method="post" id="search_global_form">
                @csrf
                <select class="selectpicker" name="type_search_global">
                    <option selected value="article">{{__('По артиклю,названию')}}</option>
                    <option value="vin">{{__('По vin')}}</option>
                </select>
                <input type="search" name="vin" placeholder="Строка поиска">
                <button class="submit" type="submit"><i class="icon-magnifier"></i></button>
            </form>
            <script>
                $(document).ready(function () {
                    $('#search_global_form').submit(function (e) {
                        if ($(this).find('select').val() === 'article'){
                            e.preventDefault();
                            location.href = `{{route('catalog',['category' => null])}}?search_product_article=${$(this).find('input[type="search"]').val()}`;
                        }
                    });
                });
            </script>
        </div>

        <!-- Cart Part -->
        <ul class="nav navbar-right cart-pop">
            <li>
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
            <div class="cate-lst"> <a  data-toggle="collapse" class="cate-style" href="#cater"><i class="fa fa-list-ul"></i> {{__('Каталог')}} </a>
                <div class="cate-bar-in">
                    <div id="cater" class="collapse">
                        <ul class="list-group root-list">
                            <li class="list-group-item">
                                <a class="root-link" onclick="getSub('passenger',null,this)" href="#.">{{__('Легковой')}}</a>
                                <ul class="list-group" style="display: none">
                                    <li style="text-align: center;">
                                        <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                        <span class="sr-only">Loading...</span>
                                    </li>
                                </ul>
                            </li>
                            <li  class="list-group-item">
                                <a class="root-link" onclick="getSub('commercial',null,this)" href="#.">{{__('Грузовой')}}</a>
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


                            function getSub(type,id = null,obj) {
                                if (id === null) {
                                    $.get(`{{route('get_subcategory')}}?type=${type}`,function (data) {
                                        let data_str = '';
                                        data.subCategory.forEach(function (item) {
                                            data_str += `<li class="list-group-item child-list-group-item">
                                                            <a class="root-link" onclick="getSub('${type}','${item.assemblygroupdescription}',this)" href="#.">${item.assemblygroupdescription}</a>
                                                                <ul class="list-group" style="display: none">
                                                                    <li style="text-align: center;">
                                                                        <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                                                        <span class="sr-only">Loading...</span>
                                                                    </li>
                                                                </ul>
                                                            </li>`;
                                        });
                                        $($(obj).siblings("ul")).html(data_str);
                                    });
                                } else {
                                    if (typeof id === 'string'){
                                        $.get(`{{route('get_subcategory')}}?type=${type}&category=${id}&level=assemblygroupdescription`,function (data) {
                                            let data_str = '';
                                            data.subCategory.forEach(function (item,i,array) {
                                                data_str += `<li class="list-group-item child-list-group-item">
                                                                <a href="{{route('catalog')}}/${item.id}?type=${type}">${(array[i].normalizeddescription === array[(array.length !== i + 1 ?i + 1:i)].normalizeddescription)?item.usagedescription:item.normalizeddescription}</a>
                                                            </li>`;
                                            });
                                            $($(obj).siblings("ul")).html(data_str);
                                        });
                                    } else {

                                    }
                                }
                            }
                        </script>
                    </div>
                </div>
            </div>

            <!-- Navbar Header -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav-open-btn" aria-expanded="false"> <span><i class="fa fa-navicon"></i></span> </button>
            </div>
            <!-- NAV RIGHT -->
            <div class="nav-right"> <span class="call-mun"><i class="fa fa-phone"></i> <strong>{{__('Гарячая линия:')}}</strong> {{config('app.company_phone')}}</span> </div>
        </div>
    </nav>
</header>