<table class="table table-top-countries">
    <tbody>
    <tr>
        <td>{{__('ФИО')}}</td>
        <td class="text-right">
            <input type="text" name="fio" value="{{$user->fio}}">
        </td>
    </tr>
    <tr>
        <td>{{__('E-mail')}}</td>
        <td class="text-right">
            <input type="email" name="email" value="{{$user->email}}">
        </td>
    </tr>
    <tr>
        <td>{{__('Страна')}}</td>
        <td class="text-right">
            <input type="text" name="delivery_country" value="@isset($location->delivery_country){{$location->delivery_country}}@endisset">
        </td>
    </tr>
    <tr>
        <td>{{__('Город')}}</td>
        <td class="text-right">
            <input type="text" name="delivery_city" value="@isset($location->delivery_city){{$location->delivery_city}}@endisset">
        </td>
    </tr>
    <tr>
        <td>{{__('Отделение почты')}}</td>
        <td class="text-right">
            <input type="text" name="delivery_department" value="@isset($location->delivery_department){{$location->delivery_department}}@endisset">
        </td>
    </tr>
    <tr>
        <td>{{__('Телефон')}}</td>
        <td class="text-right">
            <input type="tel" name="phone" value="{{$user->phone}}">
            @isset($dop_phone) @foreach($dop_phone as $val) {{__(', ' . $val->phone)}} @endforeach @endisset</td>
    </tr>
    <tr>
        <td>{{__('Подтвержден')}}</td>
        <td class="text-right">
            @if(isset($user->email_verified_at))
                <i class="fa fa-check text-success" aria-hidden="true"></i>
            @else
                <i class="fa fa-times text-danger" aria-hidden="true"></i>
            @endif
        </td>
    </tr>
    </tbody>
</table>
<button type="submit" class="btn btn-success btn-sm">
    <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
</button>
