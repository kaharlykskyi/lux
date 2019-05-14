@extends('admin.layouts.admin')

@section('content')

    <div class="container-fluid m-t-75">
        @if (session('status'))
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-info" role="alert">
                        {{ session('status') }}
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <!-- DATA TABLE -->
                <h3 class="title-5 m-b-35 m-t-15">{{__('СТО база')}}</h3>
                <div class="table-data__tool">
                    <div class="table-data__tool-left">
                    </div>
                    <div class="table-data__tool-right">
                        <button data-toggle="modal" data-target="#companySettingsModal" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                            <i class="fa fa-refresh" aria-hidden="true"></i>{{__('Обновить данные ФОП')}}</button>

                        <button onclick="location.href = '{{route('admin.sto_manager.create')}}'" class="au-btn au-btn-icon au-btn--green au-btn--small">
                            <i class="zmdi zmdi-plus"></i>{{__('Создать клиента')}}</button>
                    </div>
                </div>
                <div class="row m-t-10 m-b-10">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body card-block">
                                <form action="{{route('admin.sto_manager.index')}}" method="get" id="filter_sto" style="font-size: 13px;">
                                    <div class="row form-group">
                                        <div class="col col-sm-4">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="fio_user" class=" form-control-label">ФИО</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <input type="text" id="fio_user" value="{{request()->query('fio_user')}}" name="fio_user" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col-sm-4">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="date_crate" class=" form-control-label">Дата</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <input type="date" id="date_crate" value="{{request()->query('date_crate')}}" name="date_crate" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col-sm-4">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="phone_user" class=" form-control-label">Телефон</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <input type="text" id="phone_user" value="{{request()->query('phone_user')}}" name="phone_user" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <button onclick="$('#filter_sto').submit();" class="btn btn-primary btn-sm">
                                    <i class="fa fa-dot-circle-o"></i> Фильтровать
                                </button>
                                <button onclick="location.href = '{{route('admin.sto_manager.index')}}'" class="btn btn-danger btn-sm">
                                    <i class="fa fa-ban"></i> Отменить
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                        <tr>
                            <th>{{__('ID')}}</th>
                            <th>{{__('ФИО')}}</th>
                            <th>{{__('Телефон')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($clients)
                            @forelse($clients as $client)
                                <tr class="tr-shadow">
                                    <td>{{$client->id}}</td>
                                    <td>
                                        <span class="block-email">{{$client->fio}}</span>
                                    </td>
                                    <td>{{$client->phone}}</td>
                                    <td>
                                        <div class="table-data-feature">
                                            <button onclick="location.href = '{{route('admin.sto_check_manager.index',['client' => $client->id])}}'" class="item" data-toggle="tooltip" data-placement="bottom" title="{{__('Все чеки')}}">
                                                <i class="fa fa-th-list" aria-hidden="true"></i>
                                            </button>
                                            <button onclick="location.href = '{{route('admin.sto_manager.edit',$client->id)}}'" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Редактирвать')}}">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <form onsubmit="if(confirm('DELETE?')){return true}else{return false}"
                                                  action="{{route('admin.sto_manager.destroy',$client->id)}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Удалить')}}">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="spacer"></tr>
                            @empty
                                <tr class="tr-shadow">
                                    <td colspan="10">
                                        <div class="alert alert-warning" role="alert">
                                            {{__('База СТО пуста')}}
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        @endisset

                        </tbody>
                    </table>
                </div>
                <!-- END DATA TABLE -->
            </div>
            <div class="col-sm-12">
                {{$clients->links()}}
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

    @include('admin.component.company_settings')

@endsection
