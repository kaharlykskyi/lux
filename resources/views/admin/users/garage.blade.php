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
            <div class="col-sm-12">
                <!-- USER DATA-->
                <div class="user-data">
                    <h3 class="title-3 m-b-30">
                        <i class="fa fa-car" aria-hidden="true"></i>{{__('Гараж пользователя - ' . $user->name)}}</h3>
                    <div class="col-12 m-b-15">
                        <a href="{{route('admin.users')}}" class="btn btn-success">{{__('Назад')}}</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 m-t-15">
                <table class="table table-borderless table-striped table-earning">
                    <thead>
                    <tr>
                        <th>{{__('марка')}}</th>
                        <th>{{__('модель')}}</th>
                        <th>{{__('модификация')}}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($cars as $item)
                        @php
                            $tecdoc = new \App\TecDoc\Tecdoc('mysql_tecdoc');
                            $tecdoc->setType($item->type_auto);
                            $marka = $tecdoc->getBrandById((int)$item->brand_auto);
                            $model = $tecdoc->getModelById((int)$item->model_auto);
                            $modif = $tecdoc->getModificationById((int)$item->modification_auto);
                        @endphp
                        <tr>
                            <td>{{$marka[0]->name}}</td>
                            <td>{{$model[0]->name}}</td>
                            <td>{{$modif[0]->name}}</td>
                            <td style="font-size: 12px;">
                                <a href="{{route('admin.user.garage',['user' => $user->id,'car' => $item->id])}}">
                                    <i class="fa fa-book" aria-hidden="true"></i>
                                    Полная информация
                                </a><br>
                                <a href="{{route('admin.user.garage',['user' => $user->id,'delete_car' => $item->id])}}">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                    Удалить
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="alert alert-info margin-15" role="alert">
                                    {{__('Машин ещё не добавлено')}}
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @component('admin.component.footer')@endcomponent
    </div>

@endsection
