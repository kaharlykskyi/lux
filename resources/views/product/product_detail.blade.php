@extends('layouts.app')

@section('content')

    <div id="content">

        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => isset($product)?$product->name:$product_data->NormalizedDescription]
            ]
        ])
        @endcomponent

        <!-- Products -->
        <section class="padding-top-40 padding-bottom-60">
            <div class="container">
                {{--@php dump($product_vehicles) @endphp--}}
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
                                        <h5>{{isset($product)?$product->name:$product_data->NormalizedDescription}}</h5>
                                        <div class="row">
                                            @isset($product->old_price)
                                                <div class="col-sm-6">
                                                    <span class="tags" style="text-decoration: line-through;">{{$product->old_price}} грн</span>
                                                    <br>
                                                    <span class="price">{{$product->price}} грн</span>
                                                </div>
                                            @endisset
                                            <div class="col-sm-6">
                                                <p class="font-size-12-440">{{__('Достубность')}}:
                                                    @if(isset($product))
                                                        <span class="in-stock">{{__('В наличии')}}</span>
                                                    @else
                                                        <span class="text-danger">{{__('Нет на складе')}}</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <!-- List Details -->
                                        @isset($product)<div class="row">
                                            <div class="col-sm-12 padding-top-10 padding-bottom-10">
                                                {!! $product->short_description !!}
                                            </div>
                                        </div>
                                        <!-- Quinty -->
                                        <div class="add-car-block">
                                            <div class="quinty">
                                                <input type="number" value="1" id="quinty-product">
                                            </div>
                                            <a href="#" class="btn-round" onclick="addCart();return false;">
                                                <i class="icon-basket-loaded margin-right-5"></i><span class="hidden-xs hidden-sm hidden-md">{{__('Добавить в корзину')}}</span>
                                            </a>
                                        </div>

                                        <a href="#." class="btn-round" style="background: #bbbbbb;" onclick="$('.fast-buy-block').show()">
                                            <i class="ion-ios-stopwatch margin-right-5"></i><span class="font-size-11-440">{{__('Быстрый заказ')}}</span>
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
                                        @endisset
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
                                    <li role="presentation" class="active"><a href="#pro-detil"  role="tab" data-toggle="tab">{{__('Описание продукта')}}</a></li>
                                    <li role="presentation"><a href="#cus-rev"  role="tab" data-toggle="tab">{{__('Отзывы')}}</a></li>
                                    <li role="presentation"><a href="#ship" role="tab" data-toggle="tab">{{__('Доставка и оплата')}}</a></li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="pro-detil">
                                        <!-- List Details -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="table">
                                                    <tbody>
                                                    @foreach($product_attr as $item)
                                                        <tr>
                                                            <td>{{($item->displaytitle === ''?$item->description:$item->displaytitle)}}</td>
                                                            <td>{{$item->displayvalue}}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="cus-rev"></div>
                                    <div role="tabpanel" class="tab-pane fade" id="ship"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Related Products -->
                        @component('product.component.related')

                        @endcomponent
                    </div>
                </div>
            </div>
        </section>

    </div>
    @isset($product)
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
    @endisset
@endsection