<table class="table table-top-countries">
    <tbody>
    <tr>
        <td>{{__('Фамилия')}}</td>
        <td class="text-right">
            <input type="text" name="sername" value="{{$user->sername}}">
        </td>
    </tr>
    <tr>
        <td>{{__('Имя')}}</td>
        <td class="text-right">
            <input type="text" name="name" value="{{$user->name}}">
        </td>
    </tr>
    <tr>
        <td>{{__('Отчество')}}</td>
        <td class="text-right">
            <input type="text" name="last_name" value="{{$user->last_name}}">
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
            @isset($location->flag) <img style="width: 40px;" src="{{$location->flag}}" alt="{{$location->country}}"> @endisset
            @isset($location->country){{$location->country}}@endisset
        </td>
    </tr>
    <tr>
        <td>{{__('Город')}}</td>
        <td class="text-right">@isset($location->city){{$location->city}}@endisset</td>
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
