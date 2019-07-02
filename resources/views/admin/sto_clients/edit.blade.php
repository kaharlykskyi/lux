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
                        <strong>{{__('Редактирование ')}}</strong> <em>{{__('клиента СТО - ' . $sto_client->fio)}}</em>
                    </div>
                    <div class="card-body card-block">
                        <form action="{{route('admin.sto_manager.update',$sto_client->id)}}" id="sto_client_form" method="post" class="form-horizontal">
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
