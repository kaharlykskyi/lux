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
                <a href="{{route('admin.sto_manager.index')}}" class="btn btn-success">{{__('Назад')}}</a>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <strong>{{__('Редактирование ')}}</strong> <em>{{__('запсиси СТО - ' . $sto_client->fio)}}</em>
                    </div>
                    <div class="card-body card-block">
                        <form action="{{route('admin.sto_manager.update',$sto_client->id)}}" method="post" class="form-horizontal">
                            @method('PUT')
                            @csrf

                            @include('admin.sto_clients.partrials.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection
