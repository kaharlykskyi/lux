@extends('layouts.app')

@section('content')

    <div id="content">
        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => $page->title]
            ]
        ])
        @endcomponent

        <div class="container">
            <div class="row">
                <div class="col-sm-12 page-content">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>

@endsection