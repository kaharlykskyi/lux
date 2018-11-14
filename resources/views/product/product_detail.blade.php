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

        {{$product}}

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
                                    <div class="col-xs-7"> <span class="tags">Smartphones</span>
                                        <h5>{{$product->name}}</h5>
                                        <p class="rev"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <span class="margin-left-10">5 Review(s)</span></p>
                                        <div class="row">
                                            <div class="col-sm-6"><span class="price">{{$product->price}} грн</span></div>
                                            <div class="col-sm-6">
                                                <p>Availability: <span class="in-stock">In stock</span></p>
                                            </div>
                                        </div>
                                        <!-- List Details -->
                                        <ul class="bullet-round-list">
                                            <li>Screen: 1920 x 1080 pixels</li>
                                            <li>Processor: 2.5 GHz None</li>
                                            <li>RAM: 8 GB</li>
                                            <li>Hard Drive: Flash memory solid state</li>
                                            <li>Graphics : Intel HD Graphics 520 Integrated</li>
                                            <li>Card Description: Integrated</li>
                                        </ul>
                                        <!-- Colors -->
                                        <div class="row">
                                            <div class="col-xs-5">
                                                <div class="clr"> <span style="background:#068bcd"></span> <span style="background:#d4b174"></span> <span style="background:#333333"></span> </div>
                                            </div>
                                            <!-- Sizes -->
                                            <div class="col-xs-7">
                                                <div class="sizes"> <a href="#.">S</a> <a class="active" href="#.">M</a> <a href="#.">L</a> <a href="#.">XL</a> </div>
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
                                            <input type="number" value="01">
                                        </div>
                                        <a href="#." class="btn-round"><i class="icon-basket-loaded margin-right-5"></i> Add to Cart</a> </div>
                                </div>
                            </div>

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
                                        <ul class="bullet-round-list">
                                            <li>Power Smartphone 7s G930F 128GB International version - Silver</li>
                                            <li> 2G bands: GSM 850 / 900 / 1800 / 1900 3G bands: HSDPA 850 / 900 / 1900 / 2100 4G bands: LTE 700 / 800 / 850<br>
                                                900 / 1800 / 1900 / 2100 / 2600</li>
                                            <li> Dimensions: 142.4 x 69.6 x 7.9 mm (5.61 x 2.74 x 0.31 in) Weight 152 g (5.36 oz)</li>
                                            <li> IP68 certified - dust proof and water resistant over 1.5 meter and 30 minutes</li>
                                            <li> Internal: 128GB, 4 GB RAM </li>
                                        </ul>
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