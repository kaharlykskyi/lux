@extends('layouts.app')

@section('content')

    @component('home.component.search_cars',['search_cars' => $search_cars])@endcomponent

    <!-- Content -->
    <div id="content">
        <!-- Banner -->
        @component('home.component.banner',[
            'slides' => $slides
        ])
        @endcomponent

        <!-- Filter -->
        @component('component.filter')

        @endcomponent

        @php
            $advertising = file_exists(storage_path('app') . '/advertising_code.txt')?Storage::get('advertising_code.txt'):'';
        @endphp

        @if(isset($advertising) && !empty($advertising))
            <div class="container">
                <div class="row padding-bottom-15">
                    <div class="col-sm-12">
                        {!! $advertising !!}
                    </div>
                </div>
            </div>
        @endif

        @if(!isset($search_cars) || empty($search_cars))
            <div id="home-page-content">
                @component('home.component.main_page_links',[
                        'brands' => $brands,
                        'popular_products' => $popular_products,
                    ])
                @endcomponent
            </div>
        @endif

        <div class="container">
            <div class="row padding-bottom-15">
                <div class="col-sm-12">
                    <div class="seo_glupost text-center">
                        <a href="#">Автозапчасти</a><span> |</span>
                        <a href="#">Авто запчасти</a><span> |</span>
                        <a href="#">Запчасти</a><span> |</span>
                        <a href="#">Автозапчасти Киев</a><span> |</span>
                        <a href="#">Запчасти Киев</a><span> |</span>
                        <a href="#">Запчасти для иномарок</a><span> |</span>
                        <a href="#">Купить запчасти</a><span> |</span>
                        <a href="#">Интернет магазин автозапчастей</a><span> |</span>
                        <a href="#">Автозапчасти для иномарок</a><span> |</span>
                        <a href="#">Интернет магазин запчастей</a><span> |</span>
                        <a href="#">Купить автозапчасти</a><span> |</span>
                        <a href="#">Автозапчасти Украина</a><span> |</span>
                        <a href="#">Продажа автозапчастей</a><span> |</span>
                        <a href="#">Магазин автозапчастей</a><span> |</span>
                        <a href="#">Каталог запчастей</a><span> |</span>
                        <a href="#">Поиск запчастей</a><span> |</span>
                        <a href="#">Кузовные запчасти</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End Content -->

@endsection
