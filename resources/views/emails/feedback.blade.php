<table>
    <thead>
        <tr>
            <th>{{__('Имя')}}</th>
            <th>{{__('Телефон')}}</th>
            <th>{{__('E-mail')}}</th>
            <th>{{__('Сообщение')}}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding: 5px;">{{$name}}</td>
            <td style="padding: 5px;">{{$phone}}</td>
            <td style="padding: 5px;">@isset($userEmail) {{$userEmail}} @endisset</td>
            <td style="padding: 5px;">@isset($userMessage) {{$userMessage}} @endisset</td>
        </tr>
    </tbody>
</table>