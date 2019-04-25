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
                    @isset($category->subCategory)
                        <div class="panel panel-default">
                            <div class="panel-heading">{{$category->name}}</div>
                            <div class="panel-body">
                                <div class="row">
                                    @forelse($category->subCategory as $item)
                                        <div class="col-sm-3">
                                            <div class="product">
                                                <article>
                                                    <img class="img-responsive category-img" src="{{asset('images/catalog/' . $item->image)}}" alt="{{$item->name}}" >
                                                    @if(isset($item->tecdoc_id) && (int)$item->level > 0)
                                                        <a href="{{route('catalog',$item->hurl)}}" class="tittle text-center block category-title">{{$item->name}}</a>
                                                    @else
                                                        <a href="{{route('rubric',$item->hurl)}}" class="tittle text-center block category-title">{{$item->name}}</a>
                                                    @endif
                                                </article>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-sm-12">
                                            <div class="alert bg-warning">
                                                Нету данных
                                            </div>
                                        </div>
                                    @endforelse
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
