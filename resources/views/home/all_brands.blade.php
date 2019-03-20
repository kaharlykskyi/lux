@extends('layouts.app')

@section('content')

    <!-- Content -->
    <div id="content">
        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => 'Все бренды']
            ]
        ])
        @endcomponent

        <section class="contact-sec padding-top-40 padding-bottom-80">
            <div class="container">
                <div class="row" id="all_brands">
                    @isset($brands)
                        @foreach($brands as $k => $brand)
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <a data-id="{{$k}}" data-link="false" class="link h4 car-model" href="{{route('all_brands')}}?brand={{$brand->id}}">
                                    <span>Запчасти на {{$brand->description}}</span>
                                    @if(file_exists(public_path('images/images_carbrands/' . strtoupper(str_replace(' ','',$brand->description)) . '.png')))
                                        <img class="model-car-img" src="{{asset('images/images_carbrands/' . strtoupper(str_replace(' ','',$brand->description)) . '.png')}}" alt="{{$brand->description}}">
                                    @endif
                                </a>
                                <ul id="ul_{{$k}}" class="list-group" style="display: none">
                                    @foreach($brand->models as $model)
                                        <li class="list-group-item"><a href="{{route('all_brands')}}?brand={{$brand->id}}&model={{$model->id}}">{{$model->fulldescription}}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    @endisset
                </div>
            </div>

        </section>

        <script>
            $(document).ready(function () {
                $('#all_brands .link').click(function (e) {
                    if ($(this).attr('data-link') === 'false'){
                        $('#all_brands ul').hide();
                        $(`#all_brands #ul_${$(this).attr('data-id')}`).show();
                        e.preventDefault();
                    }
                });
            });
        </script>

    </div>
    <!-- End Content -->

@endsection
