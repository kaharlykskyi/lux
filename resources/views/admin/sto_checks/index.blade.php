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
                <h3 class="title-5 m-b-35 m-t-15">{{__('СТО база. Записи для клиента - ' . $client->fio)}}</h3>
                <div class="table-data__tool">
                    <div class="table-data__tool-left">
                    </div>
                    <div class="table-data__tool-right">
                        <button data-toggle="modal" data-target="#companySettingsModal" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                            <i class="fa fa-refresh" aria-hidden="true"></i>{{__('Обновить данные ФОП')}}</button>

                        <button onclick="location.href = '{{route('admin.sto_check_manager.create',['client' => $client->id])}}'" class="au-btn au-btn-icon au-btn--green au-btn--small">
                            <i class="zmdi zmdi-plus"></i>{{__('Создать чек')}}</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                        <tr>
                            <th>{{__('ID')}}</th>
                            <th>{{__('Сума')}}</th>
                            <th>{{__('Дата заявки')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($checks)
                            @forelse($checks as $check)
                                <tr class="tr-shadow">
                                    <td>{{$check->id}}</td>
                                    <td>
                                        <span class="block-email">{{(int)$check->sum}}</span>
                                    </td>
                                    <td>{{$check->application_date}}</td>
                                    <td>
                                        <div class="table-data-feature">
                                            <button onclick="location.href = '{{route('admin.sto_check_manager.pdf',$check->id)}}'" class="item" data-toggle="tooltip" data-placement="bottom" title="{{__('Сформировать товарный чек')}}">
                                                <i class="fa fa-print" aria-hidden="true"></i>
                                            </button>
                                            <button onclick="location.href = '{{route('admin.sto_check_manager.edit',$check->id)}}'" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Редактирвать')}}">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <form onsubmit="if(confirm('DELETE?')){return true}else{return false}"
                                                  action="{{route('admin.sto_check_manager.destroy',$check->id)}}" method="post">
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
                                            {{__('Записей по даному клиенту ещё нету')}}
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
                {{$checks->links()}}
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

    @include('admin.component.company_settings')

@endsection
