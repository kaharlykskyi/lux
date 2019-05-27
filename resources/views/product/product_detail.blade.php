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
                                                </ul>
                                            </div>
                                        </article>
                                    </div>
                                    <!-- Item Content -->
                                    <div class="col-xs-7">
                                        <h5>{{$product->name}}</h5>
                                        <div class="row">
                                            @if(isset($product->old_price) && $product->old_price > 0)
                                                <div class="col-sm-6">
                                                    <span class="tags" style="text-decoration: line-through;">{{(int)$product->old_price}} грн</span>
                                                    <br>
                                                    <span class="price">{{(int)$product->price}} грн</span>
                                                </div>
                                            @else
                                                <div class="col-sm-6">
                                                    <span class="price">{{(int)$product->price}} грн</span>
                                                </div>
                                            @endif
                                            <div class="col-sm-6">
                                                <p class="font-size-12-440">{{__('Доступность')}}:
                                                    @if((int)$product->count > 0)
                                                        <span class="in-stock">{{__('В наличии')}}</span>
                                                    @else
                                                        <span class="text-danger">{{__('Нет на складе')}}</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <!-- List Details -->
                                        @isset($product)
                                            <div class="row">
                                                <div class="col-sm-12 padding-top-10 padding-bottom-10">
                                                    <p>{!! $product->short_description !!}</p>
                                                </div>
                                                @isset($supplier_details)
                                                    <div class="col-sm-12 padding-top-10 padding-bottom-10">
                                                        <span class="small">{{__('Информация про поставщика')}}:</span>
                                                        <p>{{__('Название: ') . $supplier_details->name1}}</p>
                                                    </div>
                                                @endisset
                                            </div>
                                            <!-- Quinty -->
                                            <div class="add-car-block">
                                                <div class="quinty">
                                                    <input type="number" value="1" id="quinty-product">
                                                </div>
                                                <a href="#" class="btn-round" onclick="{{(int)$product->count > 0?'addCart();return false;':'alert(\'Извините, данный товар отсутсвует на складе\')'}}">
                                                    <i class="icon-basket-loaded margin-right-5"></i><span class="hidden-xs hidden-sm hidden-md">{{__('Добавить в корзину')}}</span>
                                                </a>
                                            </div>
                                            @if((int)$product->count > 0)
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
                                            @endif
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
                                    <li role="presentation"><a href="#product_vehicles"  role="tab" data-toggle="tab">{{__('Применимость детали')}}</a></li>
                                    <li role="presentation"><a href="#cus-rev"  role="tab" data-toggle="tab">{{__('Отзывы(' . count($product->comment) . ')')}}</a></li>
                                    <li role="presentation"><a href="#ship" role="tab" data-toggle="tab">{{__('Доставка и оплата')}}</a></li>
                                </ul>

                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="pro-detil">
                                        <!-- List Details -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                @isset($product_attr)
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
                                                @endisset
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="table-responsive tab-pane fade" id="product_vehicles">
                                        <table class="table table-striped">
                                            <tbody>
                                                @isset($product_vehicles)
                                                    @foreach($product_vehicles as $data)
                                                        @foreach($data as $item)
                                                            <tr>
                                                                <td>{{$item->make}}</td>
                                                                <td>{{$item->model}}</td>
                                                                <td>{{$item->description}}</td>
                                                                <td>{{$item->constructioninterval}}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                @endisset
                                            </tbody>
                                        </table>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="cus-rev">
                                        @include('product.partirals.comments')
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="ship">
                                        {!! Storage::get('shipping_payment.txt') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Related Products -->
                        @component('product.component.related',[
                            'accessories' => isset($accessories)?$accessories:null,
                            'art_replace' => isset($art_replace)?$art_replace:null
                        ])

                        @endcomponent
                    </div>
                </div>
            </div>
        </section>

    </div>
    @isset($product)
    <script>

        $('#product-comment-form').submit(function (e) {
            e.preventDefault();
            $.post(`${$(this).attr('action')}`,$(this).serialize(),function (data) {
                if (data.error !== undefined){
                    alert(data.error);
                    return false;
                }

                $('#comment_list').append(`
                    <li class="media">
                        <div class="media-body">
                             <h6>${data.user} <span><i class="fa fa-bookmark-o"></i> ${data.response.created_at} </span> </h6>
                             <p>${data.response.text}</p>
                             <p class="rev">
                                 <i class="fa ${data.response.rating > 0?'fa-star':'fa-star-o'}"></i>
                                 <i class="fa ${data.response.rating > 1?'fa-star':'fa-star-o'}"></i>
                                 <i class="fa ${data.response.rating > 2?'fa-star':'fa-star-o'}"></i>
                                 <i class="fa ${data.response.rating > 3?'fa-star':'fa-star-o'}"></i>
                                 <i class="fa ${data.response.rating > 4?'fa-star':'fa-star-o'}"></i>
                             </p>
                        </div>
                    </li>
                `);
            });
        });

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
                    $('#total-price').text(`${data.response.sum} грн`);
                    $('#count-product-mini-cart').text(data.response.count);
                    alert(data.response.save);
                }
            });
        }

        function addCartRelate(hurl) {
            $.post(hurl,{'product_count':1,'_token':'{{csrf_token()}}'}, function (data) {
                console.log(data.response);
                $('#total-price').text(`${data.response.sum} грн`);
                alert(data.response.save);
            });
        }
    </script>
    @endisset
@endsection
