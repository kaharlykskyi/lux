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
            @include('admin.component.back')
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{{__('Редактирование ')}}</strong> <em>{{__('чека № - ' . $check->id)}}</em>
                    </div>
                    <div class="card-body card-block">
                        <form action="{{route('admin.sto_check_manager.update',$check->id)}}" method="post" class="form-horizontal">
                            @method('PUT')
                            @csrf

                            @include('admin.sto_checks.partrials.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection
