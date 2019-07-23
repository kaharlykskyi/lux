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
                        @isset($sub_categories)
                            <ul class="list-group">
                                @foreach($sub_categories as $category)
                                    <li class="list-group-item margin-bottom-15 row category-car">
                                        <h6 class="text-uppercase">{{isset($category['root']->name)?$category['root']->name:$category['root']}}</h6>
                                        <div class="list-group col-xs-12 col-sm-8 row">
                                            @foreach($category['sub'] as $sub)
                                                @php
                                                    if (!empty($sub->usagedescription)){
                                                        $name=$sub->usagedescription;
                                                    }elseif (!empty($sub->normalizeddescription)){
                                                        $name=$sub->normalizeddescription;
                                                    }elseif (!empty($sub->description)){
                                                        $name=$sub->description;
                                                    }
                                                @endphp
                                                @if (isset($sub->count_product) && $sub->count_product > 0)
                                                    <a class="border-0 col-xs-12 col-sm-6 list-group-item" style="@if(isset($sub->count_product) && $sub->count_product==0) opacity: 0.6; @endif" href="{{route('catalog',$sub->id)}}?car={{$modification}}">
                                                        {{$name}} - [<span class="small text-danger">{{isset($sub->count_product)?$sub->count_product:0}}</span>]
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="col-sm-4 hidden-xs">
                                            @isset($category['root']->image)
                                                <div class="category-img" style="background-image: url('{{asset('images/catalog/' . $category['root']->image)}}')">

                                                </div>
                                            @endisset
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endisset
                    </div>
                </div>
            </div>
        </section>

    </div>
    <!-- End Content -->

@endsection
