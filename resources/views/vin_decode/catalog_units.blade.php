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
            (object)['title' => (!empty($list_units->data) && isset($search_info))?$list_units->data->catalog . ' - ' . $search_info->name:'Поиск по VIN']
        ]
    ])
    @endcomponent

    <div class="container margin-top-20">
        @if(session('status'))
            <div class="row">
                <div class="alert alert-danger" role="alert">{{session('status')}}</div>
            </div>
        @endif
        <div class="row margin-bottom-15">
            <div class="col-sm-12">
                <ul class="nav nav-pills">
                    <li role="presentation"><a href="{{str_replace('&task=units','&task=qdetails',request()->fullUrl())}}">Поиск по группам</a></li>
                    <li role="presentation" class="active"><a href="#">Поиск по категориям</a></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5" id="vin_decode_menu">
                @if (!empty($sort_categories))
                    @foreach ($sort_categories['root'] as $row)
                        @component('vin_decode.partials.menu_units',[
                            'row' => $row,
                            'catalog' => $list_units->data->catalog,
                            'vehicle_id' => $list_units->data->vehicle_id,
                            'sort_categories' => $sort_categories
                        ])@endcomponent
                    @endforeach
                @endif
            </div>
            <div class="col-md-7" id="panels_quick_group_info">
                @if (!empty($list_units))
                    @foreach ($list_units->data->list as $item)
                        @php $item_arr = get_object_vars($item) @endphp
                        <div class="col-md-4 col-sm-3">
                            <div class="panel panel-default">
                                <div class="panel-body" style="min-height: 210px;">
                                    <img style="width: 100%" src="{{str_replace('%size%','175',$item_arr['@imageurl'])}}" alt="{{$item_arr['@name']}}">
                                    <p style="font-size: 13px;">
                                        <a href="{{route('vin_decode.catalog.page')}}?ssd={{$item_arr['@ssd']}}$&unit_id={{$item_arr['@unitid']}}&catalog={{$list_units->data->catalog}}&vehicle_id={{$list_units->data->vehicle_id}}&task=unit&wizard=null&wizard2=null">
                                            <strong>{{$item_arr['@code']}}</strong> {{$item_arr['@name']}}
                                        </a>
                                    </p>
                                </div>
                            </div>
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
                if (data.list_units.data.list.length > 0){
                    html = '';
                    data.list_units.data.list.forEach(function (item) {
                        html +=`
                            <div class="col-md-4 col-sm-3">
                                <div class="panel panel-default">
                                    <div class="panel-body" style="min-height: 210px;">
                                        <img style="width: 100%" src="${item['@imageurl'].replace(/%size%/,'175')}" alt="${item['@name']}">
                                        <p style="font-size: 13px;">
                                            <a href="{{route('vin_decode.catalog.page')}}?ssd=${item['@ssd']}$&unit_id=${item['@unitid']}&catalog=${data.list_units.data.catalog}&vehicle_id=${data.list_units.data.vehicle_id}&task=unit&wizard=null&wizard2=null">
                                                <strong>${item['@code']}</strong> ${item['@name']}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>`
                    })
                    $('#panels_quick_group_info').html(html);
                } else {
                    $('#panels_quick_group_info').html('<div class="text-center">Данных не обнаружено</div>');
                }
            })
        })
    </script>
@stop
