@extends('admin.layouts.admin')

@section('content')

    <div class="container-fluid m-t-75">
        @if (session('status'))
            <div class="row p-t-10">
                <div class="col-sm-12">
                    <div class="alert alert-info" role="alert">
                        {{ session('status') }}
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="row">
                @include('admin.component.back')
            </div>
        </div>
        <div class="row p-t-10">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{{__('Создание ')}}</strong> {{__('категории')}}
                    </div>
                    <div class="card-body card-block">
                        <form action="{{route('admin.car_categories.store')}}" method="post" enctype="multipart/form-data" class="form-horizontal">
                            @csrf

                            @include('admin.car_categories.partrials.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection
