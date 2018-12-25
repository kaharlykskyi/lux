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
            <div class="col-xs-12 margin-top-10">
                <iframe onload="resizeIframe(this)" scrolling="no" src="{{route('vin_decode.catalog.page_data')}}" style="width: 100%; border: none;overflow: hidden;"></iframe>
                <script>
                    function resizeIframe(obj) {
                        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
                    }
                </script>
            </div>
        </div>
    </div>

@endsection