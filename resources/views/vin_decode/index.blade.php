@extends('layouts.app')

@section('content')

    <!-- Linking -->
    @component('component.breadcrumb',[
        'links' => [
            (object)['title' => 'Поиск по VIN']
        ]
    ])
    @endcomponent

    <div class="container margin-top-20">
        <div class="row">
            <div class="col-12 filter-section padding-10">
                <form action="{{route('vin_decode')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-xs-12 col-sm-10">
                            <input class="form-control" type="text" @isset($vin)value="{{$vin}}"@endisset name="vin" placeholder="Например: JTEHT05JX02054465">
                        </div>
                        <div class="col-xs-12 col-sm-2">
                            <button type="submit" class="btn-round btn-sm">{{__('Подобрать')}}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12">
                @if (session('status'))
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-danger" role="alert">
                                {{ session('status') }}
                            </div>
                        </div>
                    </div>
                @endif
                @isset($search_data)
                    <p class="h3">{{__('Найденые автомобили')}}</p>
                    <div class="row margin-top-10">
                        <div class="col-sm-12">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th colspan="10" scope="col">
                                        <div class="row">
                                            <div class="col-sm-12" style="display: flex; flex-wrap: wrap;">
                                                <img src="{{$search_data['img']}}" class="margin-right-10">
                                                <h6>{{$search_data['title']}}</h6>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th scope="row">1</th>
                                    <td>Mark</td>
                                    <td>Otto</td>
                                    <td>@mdo</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @php dump($search_data) @endphp
                @endisset
            </div>
        </div>
    </div>

@endsection