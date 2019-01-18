@extends('layouts.app')

@section('content')

    <div id="content">
        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => 'Каталог запчастей']
            ]
        ])
        @endcomponent

    <!-- Products -->
        <section class="padding-top-40 padding-bottom-60">
            <div class="container">
                <div class="row">

                    <!-- Shop Side Bar -->
                    <div class="col-md-3">
                        @component('catalog.compenents.filter',['brands' => $brands])@endcomponent
                    </div>

                    <!-- Products -->
                    <div class="col-md-9">

                        <!-- Short List -->
                        <div class="short-lst">
                            <h2>Cell Phones & Accessories</h2>
                            <ul>
                                <!-- Short List -->
                                <li>
                                    <p>{{isset($products)?$products->total():'0' . __(' найдено товаров')}}</p>
                                </li>
                                <!-- Short  -->
                                <li >
                                    <select class="selectpicker">
                                        <option>Show 12 </option>
                                        <option>Show 24 </option>
                                        <option>Show 32 </option>
                                    </select>
                                </li>
                                <!-- by Default -->
                                <li>
                                    <select class="selectpicker" id="sort-product">
                                        <option>{{__('Сортировать по умолчанию')}}</option>
                                        <option value="DESC">{{__('Убыванию цены')}}</option>
                                        <option value="ASC">{{__('Возростанию цены')}}</option>
                                    </select>
                                </li>
                                <script>
                                    
                                </script>
                            </ul>
                        </div>

                        <!-- Items -->
                        <div class="item-col-4">

                            @isset($products)
                                @forelse($products as $product)
                                    <!-- Product -->
                                        <div class="product" id="product-{{$product->id}}">
                                            <article> <img class="img-responsive" src="{{asset('/images/item-img-1-2.jpg')}}" alt="" >
                                                <!-- Content -->
                                                <span class="tag">{{$product->brand}}</span> <a href="{{route('product',$product->alias)}}" class="tittle">{{$product->name}}</a>
                                                <!-- Reviews -->
                                                <p class="rev"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> <i class="fa fa-star-o"></i> <span class="margin-left-10">5 Review(s)</span></p>
                                                <div class="price">{{$product->price . __(' грн')}} </div>
                                                <a href="#." class="cart-btn"><i class="icon-basket-loaded"></i></a> </article>
                                        </div>
                                @empty
                                    <div class="alert alert-warning" role="alert">{{__('Поиск не дал результатов')}}</div>
                                @endforelse
                            @endisset

                            <div class="row">
                                <div class="col-xs-12">
                                    <!-- pagination -->
                                    @isset($products){{$products->links()}}@endisset
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection