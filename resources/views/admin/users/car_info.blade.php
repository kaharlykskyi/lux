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
            <div class="col-12">
                <div class="alert alert-info">
                    Что бы начали появляться другие варианты в селектах, нужно поменять год.
                </div>
            </div>
            <div class="col-12 m-t-15">
                <form action="{{route('admin.user.garage.update',$user->id)}}" method="post" class="form-horizontal">
                    @csrf
                    <input type="hidden" name="id" value="{{$car->id}}">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <label>
                                <strong class="m-r-10">Номер кузова (VIN код):</strong>
                                <input style="border-bottom: 1px solid #c6c6c6" name="vin_code" value="{{$car->vin_code}}">
                            </label>
                        </li>
                        <li class="list-group-item">
                            <label>
                                <strong class="m-r-10">Тип:</strong>
                                <select id="type_auto" name="type_auto">
                                    <option @if($car->type_auto === 'passenger') selected @endif value="passenger">{{__('Легковой')}}</option>
                                    <option @if($car->type_auto === 'commercial') selected @endif value="commercial">{{__('Грузовой')}}</option>
                                </select>
                            </label>
                        </li>
                        <li class="list-group-item">
                            <label>
                                <strong class="m-r-10">Год:</strong>
                                <select id="year_auto" name="year_auto">
                                    @for($i=(int)date('Y');$i >= 1980;$i--)
                                        <option @if($car->year_auto === $i) selected @endif value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </label>
                        </li>
                        <li class="list-group-item">
                            <label>
                                <strong class="m-r-10">Марка:</strong>
                                <select id="brand_auto" name="brand_auto">
                                    <option selected value="{{$car->brand_auto}}">{{$marka[0]->name}}</option>
                                </select>
                            </label>
                        </li>
                        <li class="list-group-item">
                            <label>
                                <strong class="m-r-10">Модель:</strong>
                                <select id="model_auto" name="model_auto">
                                    <option selected value="{{$car->model_auto}}">{{$model[0]->name}}</option>
                                </select>
                            </label>
                        </li>
                        <li class="list-group-item">
                            <label>
                                <strong class="m-r-10">Модификация:</strong>
                                <select id="modification_auto" name="modification_auto">
                                    <option selected value="{{$car->modification_auto}}">{{$modif[0]->name}}</option>
                                </select>
                            </label>
                        </li>
                        <script>
                            $('#year_auto').change(function () {
                                $.get(`{{route('gat_brands')}}?type_auto=${$('#type_auto').val()}`,function (data) {
                                    let html = '';
                                    data.response.forEach(function (item) {
                                        html += `<option value="${item.id}">${item.description}</option>`;
                                    });
                                    $('#brand_auto').html(html);
                                });
                            });

                            $('#brand_auto').change(function () {
                                $.get(`{{route('gat_model')}}?type_auto=${$('#type_auto').val()}&brand_id=${$('#brand_auto').val()}&year_auto=${$('#year_auto').val()}`,function (data) {
                                    let html = '';
                                    data.response.forEach(function (item) {
                                        html += `<option value="${item.id}">${item.name}</option>`;
                                    });
                                    $('#model_auto').html(html);
                                });
                            });

                            $('#model_auto').change(function () {
                                $.get(`{{route('get_modifications')}}?type_auto=${$('#type_auto').val()}&model_id=${$('#model_auto').val()}`,function (data) {
                                    let html = '';
                                    data.response.forEach(function (item) {
                                        if (item.attributegroup === 'General' && item.attributetype === 'ConstructionInterval'){
                                            html += `<option value="${item.id}">${item.name}</option>`;
                                        }
                                    });
                                    $('#modification_auto').html(html);
                                });
                            });
                        </script>
                        <li class="list-group-item">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
                            </button>
                        </li>
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
                </form>
            </div>
        </div>

        @component('admin.component.footer')@endcomponent
    </div>

@endsection
