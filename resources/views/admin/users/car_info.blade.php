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
                        <i class="fa fa-car" aria-hidden="true"></i>{{__('Информация о машине пользователя - ' . $user->name)}}</h3>
                    <div class="col-12 m-b-15">
                        <a href="{{route('admin.user.garage',$user->id)}}" class="btn btn-success">{{__('Назад')}}</a>
                    </div>
                </div>
            </div>
            <div class="col-12 m-t-15">
                <ul class="list-group">
                    <li class="list-group-item"><strong class="m-r-10">Номер кузова (VIN код):</strong>{{$car->vin_code}}</li>
                    <li class="list-group-item"><strong class="m-r-10">Марка:</strong>{{$marka[0]->name}}</li>
                    <li class="list-group-item"><strong class="m-r-10">Модель:</strong>{{$model[0]->name}}</li>
                    <li class="list-group-item"><strong class="m-r-10">Модификация:</strong>{{$modif[0]->name}}</li>
                    <li class="list-group-item">
                        <strong class="m-r-10">Двигатель:</strong>
                        @foreach($modif as $item)
                            @if($item->attributetype === 'EngineType')
                                {{$item->displayvalue}}
                            @endif
                        @endforeach
                    </li>
                    <li class="list-group-item">
                        <strong class="m-r-10">Кузов:</strong>
                        @foreach($modif as $item)
                            @if($item->attributetype === 'BodyType')
                                {{$item->displayvalue}}
                            @endif
                        @endforeach
                    </li>
                    <li class="list-group-item">
                        <strong class="m-r-10">Тип вождения:</strong>
                        @foreach($modif as $item)
                            @if($item->attributetype === 'DriveType')
                                {{$item->displayvalue}}
                            @endif
                        @endforeach
                    </li>
                    <li class="list-group-item">
                        <strong class="m-r-10">Тип тормоза:</strong>
                        @foreach($modif as $item)
                            @if($item->attributetype === 'BrakeType')
                                {{$item->displayvalue}}
                            @endif
                        @endforeach
                    </li>
                    <li class="list-group-item">
                        <strong class="m-r-10">KBA-номер:</strong>
                        @foreach($modif as $item)
                            @if($item->attributetype === 'KBANumber')
                                {{$item->displayvalue}}
                            @endif
                        @endforeach
                    </li>
                </ul>
            </div>
        </div>

        @component('admin.component.footer')@endcomponent
    </div>

@endsection
