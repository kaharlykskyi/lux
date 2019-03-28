@extends('layouts.app')

@section('content')

    <!-- Content -->
    <div id="content">
        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['link' => route('all_brands') . '?brand=' . $brand[0]->id,'title' => 'Запчасти для ' . $brand[0]->name],
                (object)['title' => 'Модель ' . $model[0]->name]
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
                                @foreach($categories as $category)
                                    @php
                                        $img_data = \App\Category::where([
                                            ['tecdoc_id',$category->id],
                                            ['type','passanger']
                                        ])->first();
                                    @endphp
                                    @if(isset($category->subCategories))
                                        <li class="list-group-item margin-bottom-15 row">
                                            <h6 class="text-uppercase">{{$category->description}}</h6>
                                            <div class="list-group col-xs-12 col-sm-8 row">
                                                @foreach($category->subCategories as $sub)
                                                    <a class="border-0 col-xs-12 col-sm-6 list-group-item" href="{{route('catalog',$sub->id)}}?model={{$model[0]->id}}">{{$sub->description}}</a>
                                                @endforeach
                                            </div>
                                            <div class="col-sm-4 hidden-xs">
                                                @isset($img_data)
                                                    <div class="category-img" style="background-image: url('{{asset('images/catalog/' . $img_data->logo)}}')">

                                                    </div>
                                                @endisset
                                            </div>
                                        </li>
                                    @endif
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
