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

        <section class="contact-sec padding-top-40 padding-bottom-80">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 page-content">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        $(document).ready(function () {
            $('form').submit(function (e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                });

                $.ajax({
                    type: "POST",
                    url: "{{route('feedback')}}",
                    data: $(this).serialize(),
                    success: function (data) {
                        alert(data.response);
                    },

                });
            });
        });
    </script>

@endsection