@extends('layouts.app')

@section('content')

    @component('home.component.search_cars',['search_cars' => $search_cars])@endcomponent

    <!-- Content -->
    <div id="content">
        <!-- Filter -->
        @component('component.filter')

        @endcomponent

        @component('home.component.main_page_links',[
                'brands' => $brands,
                'models' => $models,
                'popular_products' => $popular_products
            ])
        @endcomponent

    </div>
    <!-- End Content -->

@endsection
