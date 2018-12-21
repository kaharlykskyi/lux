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
                        <strong>{{__('Создание ')}}</strong> {{__('склада')}}
                    </div>
                    <div class="card-body card-block">
                        <form action="{{route('admin.stock.update',$stock->id)}}" method="post" class="form-horizontal">
                            @method('PUT')
                            @csrf

                            @include('admin.stock.partrials.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection