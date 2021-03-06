@extends('layouts.app')

@section('content')

    <!-- Content -->
    <div id="content">
        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => 'Модификация ' . $modification[0]->name]
            ]
        ])
        @endcomponent

        <section class="contact-sec padding-top-40 padding-bottom-80">
            <div class="container">
                <div class="row">
                    @isset($categories)
                        <div class="col-xs-12">
                            <p class="h4">
                                <span>Искать запчасти в</span>
                            </p>
                            <ul class="list-group">
                                @if (!is_array($categories))
                                    <li class="list-group-item margin-bottom-15 row category-car">
                                        <h6 class="text-uppercase">{{$categories->tecdoc_name}}</h6>
                                        <div class="list-group col-xs-12 col-sm-8 row">
                                            @foreach($categories->subCategories as $sub)
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
                                                    <a class="border-0 col-xs-12 col-sm-6 list-group-item" style="@if(isset($sub->count_product) && $sub->count_product==0) opacity: 0.6; @endif" href="{{route('catalog',$sub->id)}}?car={{$modification[0]->id}}">
                                                        {{$name}} - [<span class="small text-danger">{{isset($sub->count_product)?$sub->count_product:0}}</span>]
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="col-sm-4 hidden-xs">
                                            @isset($categories->image)
                                                <div class="category-img" style="background-image: url('{{asset('images/catalog/' . $categories->image)}}')">

                                                </div>
                                            @endisset
                                        </div>
                                    </li>
                                @else
                                    @foreach($categories as $category)
                                        @php
                                            $img_data = \App\Category::where([
                                                ['tecdoc_id',$category->id],
                                                ['type','passanger']
                                            ])->first();
                                        @endphp
                                        <li class="list-group-item margin-bottom-15 row category-car">
                                            <h6 class="text-uppercase">{{$category->description}}</h6>
                                            <div class="list-group col-xs-12 col-sm-8 row">
                                                @foreach($category->subCategories as $sub)
                                                    <a class="border-0 col-xs-12 col-sm-6 list-group-item" style="@if(isset($sub->count_product) && $sub->count_product==0) opacity: 0.6; @endif" href="{{route('catalog',$sub->id)}}?modification_auto={{$modification[0]->id}}">
                                                        {{$sub->description}} - [<span class="small text-danger">{{isset($sub->count_product)?$sub->count_product:0}}</span>]
                                                    </a>
                                                @endforeach
                                            </div>
                                            <div class="col-sm-4 hidden-xs">
                                                @isset($img_data)
                                                    <div class="category-img" style="background-image: url('{{asset('images/catalog/' . $img_data->logo)}}')">

                                                    </div>
                                                @endisset
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    @endisset
                </div>
            </div>
        </section>

    </div>
    <!-- End Content -->

@endsection
