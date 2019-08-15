@extends('layouts.app')

@section('style')
    <style>
        .vin_decode_menu{
            list-style: none;
        }
        .vin_decode_menu.sub_menu{
            margin: 10px 10px 10px 30px;
            display: none;
        }
        .vin_decode_menu.sub_menu.active{
            display: block;
        }
        .vin_decode_menu_btn{
            border: 1px solid #ccc;
            background: transparent;
            line-height: 0;
            display: flex;
            float: left;
            height: 20px;
            width: 20px;
            margin-right: 10px;
            justify-content: center;
            align-items: center;
            font-size: 13px;
        }
    </style>
@stop

@section('content')

    <!-- Linking -->
    @component('component.breadcrumb',[
        'links' => [
            (object)['title' => !empty($response->data)?$response->data->search_info->brand . ' - ' . $response->data->search_info->name:'Поиск по VIN']
        ]
    ])
    @endcomponent

    <div class="container margin-top-20">
        <div class="row margin-bottom-15">
            <div class="col-sm-12">
                <ul class="nav nav-pills">
                    <li role="presentation" class="active"><a href="#">Поиск по группам</a></li>
                    <li role="presentation"><a href="{{str_replace('&task=qdetails','&task=units',request()->fullUrl())}}">Поиск по категориям</a></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5" id="vin_decode_menu">
                @if (!empty($response->data->list))
                    @foreach ($response->data->list as $row)
                        @include('vin_decode.partials.menu', ['row' => $row,'search_info' => $response->data->search_info])
                    @endforeach
                @endif
            </div>
            <div class="col-md-7" id="panels_quick_group_info">
                @if (!empty($response->data->quick_group_info->list))
                    @foreach ($response->data->quick_group_info->list as $item)
                        @php $item_arr = get_object_vars($item) @endphp
                        <div class="panel panel-default">
                            <div class="panel-heading"><h5>{{$item_arr['@name']}}</h5></div>
                            @if (is_array($item_arr['Unit']))
                                @foreach($item_arr['Unit'] as $val)
                                    @php $Unit = get_object_vars($val) @endphp
                                    @component('vin_decode.partials.quick_group_info',['Unit' => $Unit]) @endcomponent
                                @endforeach
                            @else
                                @php $Unit = get_object_vars($item_arr['Unit']) @endphp
                                @component('vin_decode.partials.quick_group_info',['Unit' => $Unit]) @endcomponent
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function showSubMenu(id) {
            $(`#vin_decode_menu_${id} > li > ul`).toggleClass('active');
            $(`#vin_decode_menu_btn_${id} .fa-plus`).toggle();
            $(`#vin_decode_menu_btn_${id} .fa-minus`).toggle();
        }

        $('#vin_decode_menu a').click(function (event) {
            event.preventDefault();
            $('#panels_quick_group_info').html('<div class="text-center"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></div>');
            $.get($(this).attr('href'),function (data) {
                const responce = JSON.parse(data);
                let html = '';
                if (responce.data.list.length > 0){
                    responce.data.list.forEach(function (item) {
                        html += `<div class="panel panel-default"><div class="panel-heading"><h5>${item['@name']}</h5></div>`;
                        if (Array.isArray(item.Unit)){
                            item.Unit.forEach(function (date) {
                                html += quick_group_info_template(date)
                            })
                        } else{
                            html += quick_group_info_template(item.Unit)
                        }
                        html += '</div>';
                    });
                    $('#panels_quick_group_info').html(html + '</div>')
                } else {
                    $('#panels_quick_group_info').html('<div class="text-center">Данных не обнаружено</div>');
                }
            })
        })

        function quick_group_info_template(data) {
            let template = '<div class="panel-body">';

            template +=`<h6>
                            <a href="{{route('vin_decode.catalog.page')}}?ssd=${data['@ssd']}$&unit_id=${data['@unitid']}&catalog={{request('catalog')}}&vehicle_id=0&task=unit&wizard=false&wizard2=true"><strong>${data['@code']}</strong>${data['@name']}</a>
                        </h6>
                        <div class="col-sm-3 padding-0">
                            <button>
                                <img style="width: 100%;" src="${data['@imageurl']!==undefined?data['@imageurl'].replace(/%size%/,'175'):''}"
                                     alt="${data['@name']}">
                            </button>
                        </div>
                        <div class="col-sm-9 padding-0">
                            <table class="grid-table" style="width: 100%;">
                                <thead style="background: #0088cc;color: #fff;">
                                <tr>
                                    <th class="padding-5">OEM</th>
                                    <th class="padding-5">Наименование детали</th>
                                </tr>
                                </thead>
                                <tbody>`;

            if (Array.isArray(data.Detail)){
                data.Detail.forEach(function (val) {
                    template += quick_group_info_template_item(val)
                })
            } else {
                template += quick_group_info_template_item(data.Detail)
            }



            return template + '</tbody></table></div></div>';
        }

        function quick_group_info_template_item(data){
            return `
                <tr>
                    <td class="padding-5" colspan="1" data-field="OEM">
                        <div class="cell-inner"><a href="{{route('catalog')}}?pcode=${data['@oem']}">${data['@oem']}</a>
                        </div>
                    </td>
                    <td class="padding-5" colspan="1" data-field="Наименование детали">
                        <div class="cell-inner"><a href="{{route('catalog')}}?pcode=${data['@oem']}">${data['@name']}</a></div>
                    </td>
                </tr>
            `;
        }
    </script>
@stop
