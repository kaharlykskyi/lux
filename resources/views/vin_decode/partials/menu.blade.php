@php $data_arr = get_object_vars($row) @endphp
<ul class="vin_decode_menu @isset($child) sub_menu @endisset" id="vin_decode_menu_{{$data_arr['@quickgroupid']}}">
    <li>
        @if(isset($data_arr['row']))
            <button onclick="showSubMenu({{$data_arr['@quickgroupid']}})" class="vin_decode_menu_btn" id="vin_decode_menu_btn_{{$data_arr['@quickgroupid']}}" type="button">
                <i class="fa fa-plus" aria-hidden="true"></i>
                <i class="fa fa-minus" aria-hidden="true" style="display: none"></i>
            </button>
        @endif
        @if ($data_arr['@link'] === 'true')
            <a href="{{route('vin_decode.quick_group')}}?catalog={{$search_info->catalog}}&category_id=undefined&quick_group_id={{$data_arr['@quickgroupid']}}&ssd={{$search_info->ssd}}&task=qdetails&vehicle_id=0">
                {{$data_arr['@name']}}
            </a>
        @else
            {{$data_arr['@name']}}
        @endif
        @if (isset($data_arr['row']))
            @if (is_array($data_arr['row']))
                @foreach ($data_arr['row'] as $item)
                    @component('vin_decode.partials.menu',['row' => $item,'child' => true,'search_info' => $search_info]) @endcomponent
                @endforeach
            @else
                @component('vin_decode.partials.menu',['row' => $data_arr['row'],'child' => true,'search_info' => $search_info]) @endcomponent
            @endif
        @endif
    </li>
</ul>
