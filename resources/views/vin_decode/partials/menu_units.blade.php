<ul class="vin_decode_menu @isset($child) sub_menu @endisset" id="vin_decode_menu_{{$row['@categoryid']}}">
    <li>
        @if($row['@childrens'] === 'true')
            <button onclick="showSubMenu({{$row['@categoryid']}})" class="vin_decode_menu_btn" id="vin_decode_menu_btn_{{$row['@categoryid']}}" type="button">
                <i class="fa fa-plus" aria-hidden="true"></i>
                <i class="fa fa-minus" aria-hidden="true" style="display: none"></i>
            </button>
        @endif
        <a href="{{route('vin_decode.units')}}?catalog={{$catalog}}&category_id={{$row['@categoryid']}}&quick_group_id=undefined&ssd={{$row['@ssd']}}&task=units&vehicle_id={{$vehicle_id}}">
            {{$row['@name']}}
        </a>
        @if ($row['@childrens'] === 'true' && isset($sort_categories['child'][$row['@categoryid']]) )
            @foreach ($sort_categories['child'][$row['@categoryid']] as $item)
                @component('vin_decode.partials.menu_units',[
                    'row' => $item,
                    'child' => true,
                    'catalog' => $catalog,
                    'sort_categories' => $sort_categories,
                    'vehicle_id' => $vehicle_id
                ]) @endcomponent
            @endforeach
        @endif
    </li>
</ul>
