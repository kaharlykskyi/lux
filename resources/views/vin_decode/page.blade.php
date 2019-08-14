@extends('layouts.app')

@section('style')
    <style>
        .unit-map-image-wrapper {
            padding: 10px;
            height: 600px;
            width: 100%;
            border-radius: 3px;
            overflow: hidden;
            border: 1px solid #e1e7ec;
            position: relative;
        }

        .unit-map-image {
            width: 100%;
            height: 100%;
        }

        .unit-map-point {
            border: 4px solid transparent;
            position: absolute;
            margin-top: 2px;
            transition: border .3s;
            background: transparent;
        }
        .unit-map-grid {
            height: 600px;
            overflow: auto;
            border: 1px solid #e1e7ec;
            border-radius: 3px;
        }
        .grid-table {
            color: #0088cc;
            font-size: 13px;
            width: 100%;
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
        .unit-map-point:hover,.unit-map-point.choose, .unit-map-point.hovered{
            border-color: #dd1a22;
        }
        .unit-map-grid tr.choose, .unit-map-grid tr.hovered,.unit-map-grid tr:hover{
            background: #0c64cc;
        }
        .unit-map-grid tr.choose a, .unit-map-grid tr.hovered a,.unit-map-grid tr:hover a{
            color: #fff !important;
        }
    </style>
@stop

@section('content')
    @php $oem_detail_unit_info =  get_object_vars($oem_detail_unit->data->info) @endphp

    <!-- Linking -->
    @component('component.breadcrumb',[
        'links' => [
            (object)[
                'title' => $oem_info->data->brand . ' - ' . $oem_detail_unit->data->search_info->name,
                'link' => route('vin_decode.catalog') . '?ssd='.$oem_detail_unit->data->search_info->ssd.'&vehicle_id=0&catalog='.$oem_info->data->catalog.'&task=qdetails&wizard=false&wizard2=true'
            ],
            (object)['title' => $oem_detail_unit_info['@name']]
        ]
    ])
    @endcomponent

    <div class="container margin-top-20">
        <div class="row padding-bottom-30">
            <div class="col-xs-12 margin-top-10">
                <div class="col-md-6">
                    <div class="unit-map-image-wrapper">
                        <div style="height: 100%;width: 100%;position: relative;touch-action: none;">
                            <div
                                style="height: 100%;width: 100%;position: relative;overflow: hidden;touch-action: none;cursor: all-scroll;-moz-user-select: none;">
                                <div style="transform: translate(0px) scale(0.2326);transform-origin: 0px 0px 0px;">
                                    <img
                                        src="{{str_replace('%size%','source',$oem_detail_unit_info['@largeimageurl'])}}"
                                        alt="">
                                    @foreach($oem_detail_unit->data->points->list as $item)
                                        @php $item_arr = get_object_vars($item)@endphp
                                        <button class="unit-map-point" name="" data-id="{{$item_arr['@code']}}"
                                                style="left: {{$item_arr['@x1']}}px; top: {{$item_arr['@y1']}}px; width: 56px; height: 72px;"
                                                title="{{$item_arr['@name']}} " type="button"></button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="grid unit-map-grid">
                        <table class="grid-table">
                            <thead>
                            <tr>
                                <th class="padding-10">Номер детали</th>
                                <th class="padding-10">OEM</th>
                                <th class="padding-10">Наименование детали</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($oem_detail_unit->data->details->list as $row)
                                    @php $row_arr = get_object_vars($row) @endphp
                                    <tr style="border-bottom: 1px solid #ccc" class="null" data-id="{{$row_arr['@codeonimage']}}">
                                        <td class="padding-10" colspan="1" data-field="Номер детали">
                                            <div class="cell-inner"><a href="{{route('catalog')}}?pcode={{$row_arr['@oem']}}">{{$row_arr['@codeonimage']}}</a></div>
                                        </td>
                                        <td class="padding-10" colspan="1" data-field="OEM">
                                            <div class="cell-inner"><a href="{{route('catalog')}}?pcode={{$row_arr['@oem']}}">{{$row_arr['@oem']}}</a></div>
                                        </td>
                                        <td class="padding-10" colspan="1" data-field="Наименование детали">
                                            <div class="cell-inner"><a href="{{route('catalog')}}?pcode={{$row_arr['@oem']}}">{{$row_arr['@name']}}</a></div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('button.unit-map-point').mouseenter(function () {
                $(`tr[data-id="${$(this).attr('data-id')}"]`).addClass('hovered')
            }).mouseleave(function () {
                $(`tr[data-id="${$(this).attr('data-id')}"]`).removeClass('hovered')
            }).click(function () {
                $(`tr[data-id="${$(this).attr('data-id')}"]`).toggleClass('choose')
                $(this).toggleClass('choose')
            })

            $('tbody tr').mouseenter(function () {
                $(`button.unit-map-point[data-id="${$(this).attr('data-id')}"]`).addClass('hovered')
            }).mouseleave(function () {
                $(`button.unit-map-point[data-id="${$(this).attr('data-id')}"]`).removeClass('hovered')
            }).click(function () {
                $(`button.unit-map-point[data-id="${$(this).attr('data-id')}"]`).toggleClass('choose')
                $(`tr[data-id="${$(this).attr('data-id')}"]`).toggleClass('choose')
            })
        })
    </script>
@stop
