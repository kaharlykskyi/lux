@extends('layouts.app')

@section('content')

    <!-- Linking -->
    @component('component.breadcrumb',[
        'links' => [
            (object)['title' => 'Поиск по VIN','link' => route('vin_decode')],
            (object)['title' => 'Поиск по каталогу - ' . (isset($vin_title)?$vin_title:'')]
        ]
    ])
    @endcomponent

    <div class="container margin-top-20">
        <div class="row">
            <div class="col-xs-12 filter-section padding-10">
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
            <div class="col-xs-12">
                @if (session('status'))
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="alert alert-danger" role="alert">
                                {{ session('status') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-xs-12 col-sm-4">
                <ul class="list-group">
                    <li class="list-group-item"><p class="h4">{{__('Катагории')}}</p></li>
                    @isset($category)
                        @foreach($category['category_title'] as $k => $item)
                            <li class="list-group-item">
                                <form action="{{route('vin_decode.catalog')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="data" value="{{$category['category_link'][$k]}}">
                                    <input type="hidden" name="vin_code" value="@isset($vin){{$vin}}@endisset">
                                    <input type="hidden" name="vin_title" value="@isset($vin_title){{$vin_title}}@endisset">
                                    <button type="submit" style="border: none;background: transparent;">{{$item}}</button>
                                </form>
                            </li>
                        @endforeach
                    @endisset
                </ul>
            </div>
            <div class="col-xs-12 col-sm-8">
                <div class="row" style="display: flex;flex-wrap: wrap;">
                    @isset($catalog_data)
                        @foreach($catalog_data['img_small'] as $k => $item)
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="thumbnail" style="width: 100%">
                                    {{--<a href="{{route('vin_decode.catalog.page')}}{{$catalog_data['catalog_link'][$k]}}">--}}
                                        <img style="display: block !important;" src="{{$item}}" alt="{{$catalog_data['catalog_title'][$k]}}">
                                    {{--</a>--}}
                                    <div class="caption">
                                        <form action="{{route('vin_decode.catalog.page')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="data" value="{{$catalog_data['catalog_link'][$k]}}">
                                            <input type="hidden" name="vin_code" value="@isset($vin){{$vin}}@endisset">
                                            <input type="hidden" name="vin_title" value="@isset($vin_title){{$vin_title}}@endisset">
                                            <button type="submit" style="border: none;border-radius: 5px; width: 100%;">{{$catalog_data['catalog_title'][$k]}}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endisset
                </div>
            </div>
        </div>
    </div>

@endsection