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

        @component('home.component.main_page_links',[
                'brands' => $brands,
                'popular_products' => $popular_products
            ])
        @endcomponent

    </div>
    <!-- End Content -->

@endsection
