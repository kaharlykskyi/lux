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
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{{__('Редактирование ')}}</strong> <em>{{__('товара ' . $product->name)}}</em>
                    </div>
                    <div class="card-body card-block">
                        <form action="{{route('admin.product.update',$product->id)}}" method="post" class="form-horizontal">
                            @csrf

                            @include('admin.product.partrials.form')
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <strong>{{__('Запасы ')}}</strong> <em>{{__('товара ' . $product->name)}}</em>
                    </div>
                    <div class="card-body card-block">
                        @include('admin.product.partrials.stock_count')
                    </div>
                </div>
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection