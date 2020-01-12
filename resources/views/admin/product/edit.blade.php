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
                <a href="{{route('admin.product.index')}}" class="btn btn-success">{{__('Назад')}}</a>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{{__('Редактирование ')}}</strong> <em>{{__('товара ' . $product->name)}}</em>
                    </div>
                    <div class="card-body card-block">
                        <form action="{{route('admin.product.update',$product->id)}}" enctype="multipart/form-data" method="post" class="form-horizontal">
                            @method('PUT')
                            @csrf

                            @include('admin.product.partrials.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection
