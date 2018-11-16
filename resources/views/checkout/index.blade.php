@extends('layouts.app')

@section('content')

    <!-- Content -->
    <div id="content">

        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => 'Оформление заказа']
            ]
        ])
        @endcomponent

        <div class="container">
            <div class="row">
                <div class="col-md-8">

                </div>
                <div class="col-md-4">

                </div>
            </div>
        </div>


    </div>
    <!-- End Content -->

@endsection