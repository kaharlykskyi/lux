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
                                            @php
                                                $count_product = 0;
                                                foreach($all_count as $item){
                                                    if (isset($sub->tecdoc_id) && $sub->tecdoc_id === $item->id){
                                                        $count_product += (int)$item->count_product;
                                                    }
                                                    if (isset($sub->subCategory) && $sub->subCategory->isNotEmpty()){
                                                        foreach ($sub->subCategory as $child){
                                                            if ($child->tecdoc_id === $item->id){
                                                                $count_product += (int)$item->count_product;
                                                            }
                                                        }
                                                    }
                                                }
                                            @endphp
                                            @if ($count_product > 0)
                                                <a class="border-0 col-xs-12 col-sm-6 list-group-item" style="@if(isset($sub['tecdoc'][0]->count_product) && $sub['tecdoc'][0]->count_product==0) opacity: 0.6; @endif" href="{{route('catalog',$sub->hurl)}}?car={{$modification}}">
                                                    {{$sub->name}} - [<span class="small text-danger">{{$count_product}}</span>]
                                                    @if (Auth::check() && (Auth::user()->permission === 'admin' || Auth::user()->permission === 'manager'))
                                                        <span style="z-index: 99;cursor: zoom-in;position: absolute;top: 0;right: 0;" onclick="window.open('{{route('admin.all_category.edit')}}?id={{$sub->id}}','_blank');return false;">
                                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                        </span>
                                                    @endif
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="col-sm-4 hidden-xs">
                                        @isset($root->logo)
                                            <div class="category-img" style="background-image: url('{{asset('images/catalog/' . $root->logo)}}')">

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
                                                    @php
                                                        $count_product = 0;
                                                        foreach($all_count as $item){
                                                            if (isset($sub->tecdoc_id ) && $sub->tecdoc_id === $item->id){
                                                                $count_product += (int)$item->count_product;
                                                            }
                                                            if (isset($sub->subCategory) && $sub->subCategory->isNotEmpty()){
                                                                foreach ($sub->subCategory as $child){
                                                                    if ($child->tecdoc_id === $item->id){
                                                                        $count_product += (int)$item->count_product;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    @if ($count_product > 0)
                                                        <a class="border-0 col-xs-12 col-sm-6 list-group-item" href="{{route('catalog',$sub->hurl)}}?car={{$modification}}">
                                                            {{$sub->name}} - [<span class="small text-danger">{{$count_product}}</span>]
                                                            @if (Auth::check() && (Auth::user()->permission === 'admin' || Auth::user()->permission === 'manager'))
                                                                <span style="z-index: 99;cursor: zoom-in;position: absolute;top: 0;right: 0;" onclick="window.open('{{route('admin.all_category.edit')}}?id={{$sub->id}}','_blank');return false;">
                                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                                </span>
                                                            @endif
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
