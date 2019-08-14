<tr>
    <td class="padding-5" colspan="1" data-field="OEM">
        <div class="cell-inner"><a href="{{route('catalog')}}?pcode={{$data['@oem']}}">{{$data['@oem']}}</a>
        </div>
    </td>
    <td class="padding-5" colspan="1" data-field="Наименование детали">
        <div class="cell-inner"><a href="{{route('catalog')}}?pcode={{$data['@oem']}}">{{$data['@name']}}</a></div>
    </td>
</tr>
