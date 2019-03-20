<div class="container">
    <div class="row padding-bottom-15">
        <div class="col-sm-12">

            <div class="main-page-links blue">
                @isset($brands)
                    <h3>Мы продаем запчасти для следующих марок автомобилей:</h3>
                    <div class="row">
                        @foreach($brands as $brand)
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <a class="link car-model" href="{{route('all_brands')}}?brand={{$brand->id}}">
                                    <span>Запчасти на {{$brand->description}}</span>
                                    @if(file_exists(public_path('images/images_carbrands/' . strtoupper(str_replace(' ','',$brand->description)) . '.png')))
                                        <img class="model-car-img" src="{{asset('images/images_carbrands/' . strtoupper(str_replace(' ','',$brand->description)) . '.png')}}" alt="{{$brand->description}}">
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <a class="link small_text" href="{{route('all_brands')}}"><em>{{__('Просмотреть для других марок')}}</em></a>
                @endisset
                @isset($models)
                    <h4>В том числе для следующих популярных моделей:</h4>
                    <div class="row">
                        @foreach($models as $model)
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <a class="link" href="{{route('all_brands')}}?brand={{$model->manufacturerid}}&model={{$model->id}}">
                                    <span>{{$model->fulldescription}}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endisset
                 @isset($popular_products)
                    <h4>Самые популярные запчасти для иномарок:</h4>
                        <div class="row">
                            @foreach($popular_products as $product)
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <a class="link" href="{{route('product',['alias' => $product->articles,'supplierid' => $product->supplierId])}}">
                                        <span>{{$product->name}}</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                @endisset
            </div>
        </div>
    </div>
</div>
