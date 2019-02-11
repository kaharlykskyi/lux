@extends('layouts.app')

@section('content')

    <!-- Content -->
    <div id="content">
        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => 'Запчасти для ' . $brand->name]
            ]
        ])
        @endcomponent

        <section class="contact-sec padding-top-40 padding-bottom-80">
            <div class="container">
                <div class="row">
                    @isset($brand)
                        <div class="col-xs-12">
                            <p class="h4">
                                <span>Модели {{$brand->name}}</span>
                            </p>
                            <ul class="list-group">
                                @foreach($brand->models as $model)
                                    <li class="list-group-item">
                                        <a href="{{route('all_brands')}}?brand={{$brand->id}}&model={{$model->id}}">{{$model->fulldescription}}</a> -
                                        <span class="small">{{$model->constructioninterval}}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endisset
                </div>
            </div>
        </section>

    </div>
    <!-- End Content -->

@endsection
