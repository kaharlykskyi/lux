@extends('layouts.app')

@section('content')

    <!-- Content -->
    <div id="content">
        <!-- Filter -->
        @component('component.filter')

        @endcomponent

        @component('home.component.main_page_links')

        @endcomponent

    </div>
    <!-- End Content -->

@endsection
