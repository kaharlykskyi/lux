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
        <input type="hidden" id="ajax_data_vin_link" value="{{route('vin_decode.catalog.ajax_data')}}">
        <input type="hidden" id="ajax_data_vin_token" value="{{csrf_token()}}">
        <div class="row">
            @component('vin_decode.components.header_catalog',['vin' => $vin])

            @endcomponent
            <div class="col-xs-12 col-sm-4 oe-tree margin-bottom-30" id="qgTree">
                {!! $category !!}
            </div>
            <div class="col-xs-12 col-sm-8">
                <div class="oe-list"></div>
            </div>
        </div>
    </div>

    <form action="{{route('vin_decode.catalog.page')}}" method="post" id="form_vin_decode_page">
        @csrf
        <input type="hidden" name="data" id="data_link_vin_decode" value="">
        <input type="hidden" name="vin_code" value="@isset($vin){{$vin}}@endisset">
        <input type="hidden" name="vin_title" value="@isset($vin_title){{$vin_title}}@endisset">
    </form>

@endsection