<div class="container">
    <div class="row">
        <div class="col-sm-12">

            <div class="main-page-links blue">
                @isset($brands)
                    <h3>Мы продаем запчасти для следующих марок автомобилей:</h3>
                    <ul class="list-group column-list">
                        @php $keys = array_rand($brands,40); @endphp
                        @foreach($keys as $key)
                            <li class="list-group-item">
                                <a class="link" href="{{route('catalog')}}?brand={{$brands[$key]->id}}">
                                    <span>Запчасти на {{$brands[$key]->description}}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endisset
                @isset($models)
                    <h4>В том числе для следующих популярных моделей:</h4>
                    <div class="table-responsive">
                        <ul class="list-group column-list">
                            @foreach($models as $model)
                                <li class="list-group-item">
                                    <a class="link" href="{{route('catalog')}}?model={{$model->id}}">
                                        <span>{{$model->fulldescription}}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endisset
                 @isset($popular_products)
                    <h4>Самые популярные запчасти для иномарок:</h4>
                    <ul class="list-group column-list">
                        @foreach($popular_products as $product)
                            <li class="list-group-item">
                                <a class="link" href="{{route('product',['alias' => $product->articles,'supplierid' => $product->supplierId])}}">
                                    <span>{{$product->name}}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endisset
            </div>
        </div>
    </div>
</div>