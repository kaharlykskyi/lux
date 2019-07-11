@extends('layouts.app')

@section('content')

    @component('home.component.search_cars',['search_cars' => $search_cars])@endcomponent

    <!-- Content -->
    <div id="content">

        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => $links
        ])
        @endcomponent

        <!-- Filter -->
        @component('component.filter',['fo_category' => $category->id])

        @endcomponent

    </div>
    <!-- End Content -->

@endsection
