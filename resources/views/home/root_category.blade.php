@extends('layouts.app')

@section('content')

    <!-- Content -->
    <div id="content">
        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => $root->title]
            ]
        ])
        @endcomponent

        <section class="contact-sec padding-top-40 padding-bottom-80">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <ul class="list-group">
                            @if (!empty($root->sub_categories))
                                <li class="list-group-item margin-bottom-15 row category-car">
                                    <h6 class="text-uppercase">{{$root->title}}</h6>
                                    <div class="list-group col-xs-12 col-sm-8 row">
                                        @foreach($root->sub_categories as $sub)
                                            @if (isset($sub['tecdoc'][0]->count_product) && $sub['tecdoc'][0]->count_product > 0)
                                                <a class="border-0 col-xs-12 col-sm-6 list-group-item" style="@if(isset($sub['tecdoc'][0]->count_product) && $sub['tecdoc'][0]->count_product==0) opacity: 0.6; @endif" href="{{route('catalog',$sub['custom_data']->tecdoc_id)}}?car={{$modification}}">
                                                    {{$sub['custom_data']->name}} - [<span class="small text-danger">{{$sub['tecdoc'][0]->count_product}}</span>]
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="col-sm-4 hidden-xs">
                                        @isset($root->logo)
                                            <div class="category-img" style="background-image: url('{{asset('images/catalog/' . $category->logo)}}')">

                                            </div>
                                        @endisset
                                    </div>
                                </li>
                            @endif
                            @if($root->childCategories->isNotEmpty())
                                @foreach($root->childCategories as $category)
                                    @if(!empty($category->sub_categories))
                                        <li class="list-group-item margin-bottom-15 row category-car">
                                            <h6 class="text-uppercase">{{$category->title}}</h6>
                                            <div class="list-group col-xs-12 col-sm-8 row">
                                                @foreach($category->sub_categories as $sub)
                                                    @if (isset($sub['tecdoc'][0]->count_product) && $sub['tecdoc'][0]->count_product > 0)
                                                        <a class="border-0 col-xs-12 col-sm-6 list-group-item" style="@if(isset($sub['tecdoc'][0]->count_product) && $sub['tecdoc'][0]->count_product==0) opacity: 0.6; @endif" href="{{route('catalog',$sub['custom_data']->tecdoc_id)}}?car={{$modification}}">
                                                            {{$sub['custom_data']->name}} - [<span class="small text-danger">{{isset($sub['tecdoc'][0]->count_product)?$sub['tecdoc'][0]->count_product:0}}</span>]
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="col-sm-4 hidden-xs">
                                                @isset($category->logo)
                                                    <div class="category-img" style="background-image: url('{{asset('images/catalog/' . $category->logo)}}')">

                                                    </div>
                                                @endisset
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </section>

    </div>
    <!-- End Content -->

@endsection
