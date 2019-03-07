@extends('layouts.app')

@section('content')

    <!-- Content -->
    <div id="content">

        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => $category->name]
            ]
        ])
        @endcomponent

        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    @isset($sub_category)
                        <div class="panel panel-default">
                            <div class="panel-heading">{{$category->name}}</div>
                            <div class="panel-body">
                                <div class="list-group">
                                    @foreach($sub_category as $item)
                                        <a href="{{route('catalog',$item->id)}}" class="list-group-item">{{$item->description}}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endisset
                </div>
            </div>
        </div>


    </div>
    <!-- End Content -->

@endsection