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
                        <button onclick="location.href = '{{route('admin.sto_manager.create')}}'" class="au-btn au-btn-icon au-btn--green au-btn--small">
                            <i class="zmdi zmdi-plus"></i>{{__('Создать')}}</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                        <tr>
                            <th>{{__('ID')}}</th>
                            <th>{{__('ФИО')}}</th>
                            <th>{{__('№ авто')}}</th>
                            <th>{{__('Дата')}}</th>
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
                                    <td>{{$client->num_auto}}</td>
                                    <td>{{$client->data}}</td>
                                    <td>
                                        <div class="table-data-feature">
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

@endsection
