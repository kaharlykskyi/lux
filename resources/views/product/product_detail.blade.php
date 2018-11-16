@extends('layouts.app')

@section('content')

    <div id="content">

        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => $product->name]
            ]
        ])
        @endcomponent

        <!-- Products -->
        <section class="padding-top-40 padding-bottom-60">
            <div class="container">
                <div class="row">

                    <!-- Products -->
                    <div class="col-md-12">
                        <div class="product-detail">
                            <div class="product">
                                <div class="row">
                                    <!-- Slider Thumb -->
                                    <div class="col-xs-5">
                                        <article class="slider-item on-nav">
                                            <div class="thumb-slider">
                                                <ul class="slides">
                                                    <li data-thumb="{{asset('images/item-img-1-1.jpg')}}"> <img src="{{asset('images/item-img-1-1.jpg')}}" alt="" > </li>
                                                    <li data-thumb="{{asset('images/item-img-1-2.jpg')}}"> <img src="{{asset('images/item-img-1-2.jpg')}}" alt="" > </li>
                                                    <li data-thumb="{{asset('images/item-img-1-3.jpg')}}"> <img src="{{asset('images/item-img-1-3.jpg')}}" alt="" > </li>
                                                </ul>
                                            </div>
                                        </article>
                                    </div>
                                    <!-- Item Content -->
                                    <div class="col-xs-7">
                                        <h5>{{$product->name}}</h5>
                                        <p class="rev"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <span class="margin-left-10">5 Review(s)</span></p>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                @isset($product->old_price)
                                                    <span class="tags" style="text-decoration: line-through;">{{$product->old_price}} грн</span>
                                                    <br>
                                                @endisset
                                                <span class="price">{{$product->price}} грн</span>
                                            </div>
                                            <div class="col-sm-6">
                                                <p>Availability: <span class="in-stock">In stock</span></p>
                                            </div>
                                        </div>
                                        <!-- List Details -->
                                        <div class="row">
                                            <div class="col-sm-12 padding-top-10 padding-bottom-10">
                                                {!! $product->short_description !!}
                                            </div>
                                        </div>
                                        <!-- Compare Wishlist -->
                                        <ul class="cmp-list">
                                            <li><a href="#."><i class="fa fa-heart"></i> Add to Wishlist</a></li>
                                            <li><a href="#."><i class="fa fa-navicon"></i> Add to Compare</a></li>
                                            <li><a href="#."><i class="fa fa-envelope"></i> Email to a friend</a></li>
                                        </ul>
                                        <!-- Quinty -->
                                        <div class="quinty">
                                            <input type="number" value="1" id="quinty-product">
                                        </div>
                                        <a href="#" class="btn-round" onclick="addCart();return false;"><i class="icon-basket-loaded margin-right-5"></i>{{__('Добавить в корзину')}}</a>
                                        <script>
                                            function addCart() {
                                                const count = $('#quinty-product').val();
                                                $.ajaxSetup({
                                                    headers: {
                                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                    },

                                                });

                                                $.ajax({
                                                    type: 'POST',
                                                    url: '{{route('add_cart',$product->id)}}',
                                                    data: `product_count=${count}`,
                                                    success: function (data) {
                                                        console.log(data.response);
                                                        $('#total-price').text(`${data.response.sum} грн`);

                                                        alert(data.response.save);
                                                    }
                                                });
                                            }
                                        </script>
                                        <a href="#." class="btn-round" style="background: #bbbbbb;" onclick="$('.fast-buy-block').show()">
                                            <i class="ion-ios-stopwatch margin-right-5"></i>{{__('Заказать по фасту')}}
                                        </a>
                                        <div class="relative">
                                            <div class="fast-buy-block" style="background: #fff;">
                                                <div class="contact-info">
                                                    <button type="button" class="close" onclick="$('.fast-buy-block').hide();"><span aria-hidden="true">&times;</span></button>
                                                    <h5 class="text-center">{{__('Быстрый заказ')}}</h5>
                                                    <p class="text-center">{{__('Оставте ваши контакты и мы свяжемся с вами')}}</p>
                                                    <hr>
                                                    <form type="POST" action="{{route('fast_buy',$product->id)}}" class="login-sec">
                                                        @csrf

                                                        <ul class="row">
                                                            <li class="col-sm-12">
                                                                <label>{{__('Введите Ваш номер телефона')}}
                                                                    <input type="tel" class="form-control" name="phone" placeholder="380xxxxxxxxx" pattern="[0-9]{12}" required>
                                                                </label>
                                                            </li>
                                                            <li class="col-sm-12">
                                                                <button type="submit" class="btn-round">{{__('Заказать')}}</button>
                                                                <button type="button" class="btn-round" onclick="$('.fast-buy-block').hide();" style="background: #bbbbbb;">{{__('Отмена')}}</button>
                                                            </li>
                                                        </ul>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function () {
                                    $('.fast-buy-block form').submit(function (e) {
                                        e.preventDefault();
                                        $.ajax({
                                            type: 'POST',
                                            url: $(this).attr('action'),
                                            data: $(this).serialize(),
                                            success: function (data) {
                                                $('.fast-buy-block form ul').html(`<p class="text-center">${data.response}</p>`);
                                            },
                                            beforeSend: function () {
                                                $('.fast-buy-block form ul').html(
                                                    `<li class="col-sm-12 text-center padding-20">
                                                         <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                                    </li>`);
                                            }
                                        });
                                    })
                                })
                            </script>

                            <!-- Details Tab Section-->
                            <div class="item-tabs-sec">

                                <!-- Nav tabs -->
                                <ul class="nav" role="tablist">
                                    <li role="presentation" class="active"><a href="#pro-detil"  role="tab" data-toggle="tab">Product Details</a></li>
                                    <li role="presentation"><a href="#cus-rev"  role="tab" data-toggle="tab">Customer Reviews</a></li>
                                    <li role="presentation"><a href="#ship" role="tab" data-toggle="tab">Shipping & Payment</a></li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="pro-detil">
                                        <!-- List Details -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                {!! $product->full_description !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="cus-rev"></div>
                                    <div role="tabpanel" class="tab-pane fade" id="ship"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Related Products -->
                        <section class="padding-top-30 padding-bottom-0">
                            <!-- heading -->
                            <div class="heading">
                                <h2>Related Products</h2>
                                <hr>
                            </div>
                            <!-- Items Slider -->
                            <div class="item-slide-4 with-nav">
                                <!-- Product -->
                                <div class="product">
                                    <article> <img class="img-responsive" src="images/item-img-1-1.jpg" alt="" >
                                        <!-- Content -->
                                        <span class="tag">Latop</span> <a href="#." class="tittle">Laptop Alienware 15 i7 Perfect For Playing Game</a>
                                        <!-- Reviews -->
                                        <p class="rev"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <span class="margin-left-10">5 Review(s)</span></p>
                                        <div class="price">$350.00 </div>
                                        <a href="#." class="cart-btn"><i class="icon-basket-loaded"></i></a> </article>
                                </div>
                                <!-- Product -->
                                <div class="product">
                                    <article> <img class="img-responsive" src="images/item-img-1-2.jpg" alt="" > <span class="sale-tag">-25%</span>

                                        <!-- Content -->
                                        <span class="tag">Tablets</span> <a href="#." class="tittle">Mp3 Sumergible Deportivo Slim Con 8GB</a>
                                        <!-- Reviews -->
                                        <p class="rev"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <span class="margin-left-10">5 Review(s)</span></p>
                                        <div class="price">$350.00 <span>$200.00</span></div>
                                        <a href="#." class="cart-btn"><i class="icon-basket-loaded"></i></a> </article>
                                </div>

                                <!-- Product -->
                                <div class="product">
                                    <article> <img class="img-responsive" src="images/item-img-1-3.jpg" alt="" >
                                        <!-- Content -->
                                        <span class="tag">Appliances</span> <a href="#." class="tittle">Reloj Inteligente Smart Watch M26 Touch </a>
                                        <!-- Reviews -->
                                        <p class="rev"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <span class="margin-left-10">5 Review(s)</span></p>
                                        <div class="price">$350.00</div>
                                        <a href="#." class="cart-btn"><i class="icon-basket-loaded"></i></a> </article>
                                </div>

                                <!-- Product -->
                                <div class="product">
                                    <article> <img class="img-responsive" src="images/item-img-1-4.jpg" alt="" > <span class="new-tag">New</span>

                                        <!-- Content -->
                                        <span class="tag">Accessories</span> <a href="#." class="tittle">Teclado Inalambrico Bluetooth Con Air Mouse</a>
                                        <!-- Reviews -->
                                        <p class="rev"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <span class="margin-left-10">5 Review(s)</span></p>
                                        <div class="price">$350.00</div>
                                        <a href="#." class="cart-btn"><i class="icon-basket-loaded"></i></a> </article>
                                </div>

                                <!-- Product -->
                                <div class="product">
                                    <article> <img class="img-responsive" src="images/item-img-1-5.jpg" alt="" >
                                        <!-- Content -->
                                        <span class="tag">Appliances</span> <a href="#." class="tittle">Funda Para Ebook 7" 128GB full HD</a>
                                        <!-- Reviews -->
                                        <p class="rev"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <span class="margin-left-10">5 Review(s)</span></p>
                                        <div class="price">$350.00</div>
                                        <a href="#." class="cart-btn"><i class="icon-basket-loaded"></i></a> </article>
                                </div>

                                <!-- Product -->
                                <div class="product">
                                    <article> <img class="img-responsive" src="images/item-img-1-6.jpg" alt="" > <span class="sale-tag">-25%</span>

                                        <!-- Content -->
                                        <span class="tag">Tablets</span> <a href="#." class="tittle">Mp3 Sumergible Deportivo Slim Con 8GB</a>
                                        <!-- Reviews -->
                                        <p class="rev"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <span class="margin-left-10">5 Review(s)</span></p>
                                        <div class="price">$350.00 <span>$200.00</span></div>
                                        <a href="#." class="cart-btn"><i class="icon-basket-loaded"></i></a> </article>
                                </div>

                                <!-- Product -->
                                <div class="product">
                                    <article> <img class="img-responsive" src="images/item-img-1-7.jpg" alt="" >
                                        <!-- Content -->
                                        <span class="tag">Appliances</span> <a href="#." class="tittle">Reloj Inteligente Smart Watch M26 Touch </a>
                                        <!-- Reviews -->
                                        <p class="rev"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <span class="margin-left-10">5 Review(s)</span></p>
                                        <div class="price">$350.00</div>
                                        <a href="#." class="cart-btn"><i class="icon-basket-loaded"></i></a> </article>
                                </div>

                                <!-- Product -->
                                <div class="product">
                                    <article> <img class="img-responsive" src="images/item-img-1-8.jpg" alt="" > <span class="new-tag">New</span>

                                        <!-- Content -->
                                        <span class="tag">Accessories</span> <a href="#." class="tittle">Teclado Inalambrico Bluetooth Con Air Mouse</a>
                                        <!-- Reviews -->
                                        <p class="rev"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <span class="margin-left-10">5 Review(s)</span></p>
                                        <div class="price">$350.00</div>
                                        <a href="#." class="cart-btn"><i class="icon-basket-loaded"></i></a> </article>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </section>

    </div>

@endsection