@extends('layouts.app')

@section('content')

    <!-- Content -->
    <div id="content">

        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => $links
        ])
        @endcomponent

        <div class="container">
            <div class="row">
                @isset($brands)
                    @foreach($brands as $brand)
                        <div class="col-xs-12 col-sm-6 col-md-3">
                            <a class="link h4 car-model" href="{{route('rubric.choose_car',$category->id)}}?brand={{$brand->id}}">
                                <span>Запчасти на {{$brand->description}}</span>
                                @if(file_exists(public_path('images/images_carbrands/' . strtoupper(str_replace(' ','',$brand->description)) . '.png')))
                                    <img class="model-car-img" src="{{asset('images/images_carbrands/' . strtoupper(str_replace(' ','',$brand->description)) . '.png')}}" alt="{{$brand->description}}">
                                @endif
                            </a>
                        </div>
                    @endforeach
                @endisset
                    @isset($models)
                        <div class="col-xs-12">
                            <p class="h4">
                                <span>Виберите модель</span>
                            </p>
                            <ul class="list-group">
                                @foreach($models as $model)
                                    <li class="list-group-item">
                                        <a href="{{route('rubric.choose_car',$category->id)}}?brand={{request('brand')}}&model={{$model->id}}">{{$model->fulldescription}}</a> -
                                        <span class="small">{{$model->constructioninterval}}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endisset
                    @isset($modifs)
                        <div class="col-xs-12">
                            <ul class="list-group">
                                @foreach($modifs as $item)
                                    @if($item->attributegroup === 'General' && $item->attributetype === 'ConstructionInterval')
                                        <li class="list-group-item margin-bottom-15 row">
                                            <a href="{{route('catalog',$category->id)}}?car={{$item->id}}">
                                                <h6 class="text-uppercase">
                                                    {{$item->name}}<br>
                                                    <span class="small text-right">{{$item->displayvalue}}</span>
                                                </h6>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endisset
            </div>
        </div>


    </div>
    <!-- End Content -->

@endsection
