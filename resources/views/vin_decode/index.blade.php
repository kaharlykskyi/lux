@extends('layouts.app')

@section('style')
    <style>
        .car-search-grid .grid-table {
            color: #0088cc;
            font-size: 13px;
            width: 100%;
            margin: 15px 0;
        }
        .car-search-grid .grid-table tr {
            transition: background .3s;
        }
        .grid-table th {
            color: #fff;
            background: #0088cc;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 400;
            padding: 0 15px;
            height: 42px;
            vertical-align: middle;
        }
        .car-search-grid .grid-table td {
            vertical-align: middle;
            padding: 0;
            height: 40px;
        }
        .car-search-grid .grid-table a:not(.btn) {
            color: #39c;
            text-decoration: none;
            transition: color .3s;
            display: flex;
            padding: 9px 15px;
            min-height: 42px;
            height: 100%;
            align-items: center;
            justify-content: flex-start;
        }
    </style>
@stop

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
            <div class="col-sm-12 filter-section padding-10">
                <form action="{{route('vin_decode')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-xs-12 col-sm-9 col-md-10">
                            <input class="form-control" type="text" @isset($vin)value="{{$vin}}" @endisset name="vin"
                                   placeholder="Например: JTEHT05JX02054465">
                        </div>
                        <div class="col-xs-12 col-sm-3 col-md-2">
                            <button type="submit" class="btn-round btn-sm">{{__('Подобрать')}}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-12">
                <div class="grid car-search-grid table-responsive">
                    <table class="grid-table table">
                        <thead>
                        <tr>
                            <th>Бренд</th>
                            <th>Название</th>
                            <th>Годы продаж</th>
                            <th>Рынок</th>
                            <th>Двигатель</th>
                            <th>Информация о двигателе</th>
                            <th>Трансмиссия</th>
                        </tr>
                        </thead>
                        <tbody id="car_data"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            const data = JSON.parse('{!! $response !!}');
            if (data.data === undefined || data.data.list.length === 0){
                $('#car_data').html('<tr><td class="" colspan="9">Нету данных</td></tr>');
            }else {
                let html ='';
                data.data.list.forEach(function (item) {
                    let link = `{{route('vin_decode.catalog')}}?ssd=${item["@ssd"]}&vehicle_id=${item["@vehicleid"]}&catalog=${item["@catalog"]}&task=qdetails&wizard=${data.data.search_info.wizard}&wizard2=${data.data.search_info.wizard2}`;
                      html += `
                                <tr class="null    ">
                                    <td class="" colspan="1" data-field="Бренд">
                                        <div class="cell-inner">
                                            <a class="" href="${link}">${item["@brand"]}</a>
                                        </div>
                                    </td>
                                    <td class="" colspan="1" data-field="Название">
                                        <div class="cell-inner">
                                            <a class="" href="${link}">${item["@name"]}</a>
                                        </div>
                                    </td>`;

                    let prodRange = '';
                    let market = '';
                    let engine = '';
                    let engine_info = '';
                    let transmission = '';
                    item.attribute.forEach(function (val) {
                        if (val['@key'] === 'prodRange'){
                            prodRange = td_template(val,link);
                        }
                        if (val['@key'] === 'market'){
                            market = td_template(val,link);
                        }
                        if (val['@key'] === 'engine'){
                            engine = td_template(val,link);
                        }
                        if (val['@key'] === 'engine_info'){
                            engine_info = td_template(val,link);
                        }
                        if (val['@key'] === 'transmission'){
                            transmission = td_template(val,link);
                        }
                    });
                    html += `${prodRange+market+engine+engine_info+transmission}</tr><tr><td class="divider" colspan="9"></td></tr>`;
                });
                $('#car_data').html(html);
            }
        });

        function td_template(item,link) {
            return `<td class="" colspan="1" data-field="Годы продаж">
                        <div class="cell-inner"><a class="" href="${link}">${item['@value']}</a></div>
                    </td>`;
        }

    </script>

@endsection
