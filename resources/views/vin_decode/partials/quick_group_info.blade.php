<div class="panel-body">
    <h6>
        <a href="{{route('vin_decode.catalog.page')}}?ssd={{$Unit['@ssd']}}$&unit_id={{$Unit['@unitid']}}&catalog={{request('catalog')}}&vehicle_id=0&task=unit&wizard=false&wizard2=true">
            <strong>{{$Unit['@code']}}</strong>{{$Unit['@name']}}
        </a>
    </h6>
    <div class="col-sm-3 padding-0">
        <button>
            <img style="width: 100%;" src="{{str_replace('%size%','175',$Unit['@imageurl'])}}"
                 alt="{{$Unit['@name']}}">
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
            <tbody>
            @if (is_array($Unit['Detail']))
                @foreach ($Unit['Detail'] as $item)
                    @php $Unit_Detail = get_object_vars($item); @endphp
                    @component('vin_decode.partials.quick_group_info_item',['data' => $Unit_Detail])@endcomponent
                @endforeach
            @else
                @php $Unit_Detail = get_object_vars($Unit['Detail']); @endphp
                @component('vin_decode.partials.quick_group_info_item',['data' => $Unit_Detail])@endcomponent
            @endif
            </tbody>
        </table>
    </div>
</div>
