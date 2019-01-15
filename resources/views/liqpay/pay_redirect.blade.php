@extends('layouts.app')

@section('content')

    <div id="content">
        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => 'перенаправление не страницу оплаты']
            ]
        ])
        @endcomponent

        <section class="contact-sec padding-top-40 padding-bottom-80">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 hidden" id="pay-form">
                        {!! $form !!}
                    </div>
                    <div class="col-xs-12">
                        <p class="h4 text-center">{{__('Идет перенаправление на страницу оплаты')}}</p>
                    </div>
                </div>
            </div>

        </section>
    </div>

    <script>
        $(document).ready(function () {
            $('#pay-form form').submit();
        });
    </script>
    
@endsection