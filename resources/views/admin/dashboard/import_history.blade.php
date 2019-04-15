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
                <h3 class="title-5 m-b-35 m-t-15">{{__('История импортов')}}</h3>
                <div class="col-12 m-b-15">
                    <a href="{{route('admin.dashboard')}}" class="btn btn-success">{{__('Назад')}}</a>
                </div>
                <div class="table-responsive table--no-card m-b-30">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                        <tr>
                            <th>Поставщик</th>
                            <th>Удачно импортировано</th>
                            <th>Ошибки при импорте</th>
                            <th class="text-right">Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($history_imports)
                            @forelse($history_imports as $import)
                                <tr>
                                    <td>{!! $import->company !!}</td>
                                    <td>{{$import->success}}</td>
                                    <td>{{$import->fail}}</td>
                                    <td class="text-right">{{$import->created_at}}</td>
                                </tr>
                            @empty
                                <tr class="tr-shadow">
                                    <td colspan="4">
                                        <div class="alert alert-warning" role="alert">
                                            {{__('Импортов ещё не проводилось')}}
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
                {{$history_imports->links()}}
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection
