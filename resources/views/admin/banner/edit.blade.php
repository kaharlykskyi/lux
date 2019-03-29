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
        <div class="row p-t-10">
            <div class="col-12 m-b-15">
                <a href="{{route('admin.banner.index')}}" class="btn btn-success">{{__('Назад')}}</a>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{{__('Редактирование ')}}</strong> слайда
                    </div>
                    <div class="card-body card-block">
                        <form action="{{route('admin.banner.update',$banner->id)}}" method="post" enctype="multipart/form-data" class="form-horizontal">
                            @method('PUT')
                            @csrf

                            @include('admin.banner.partrials.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection
