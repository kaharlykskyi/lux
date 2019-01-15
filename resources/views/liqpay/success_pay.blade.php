@extends('layouts.app')

@section('content')

    <div id="content">
        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['title' => 'успешное пополнение баланса']
            ]
        ])
        @endcomponent

        <section class="contact-sec padding-top-40 padding-bottom-80">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-offset-3 col-sm-6">
                        <div class="alert alert-success text-center" role="alert">
                            {{__('Вы успешно пополнили баланс!')}}
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>


@endsection