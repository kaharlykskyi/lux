<div class="panel panel-primary">
    <div class="panel-heading">{{__('Личные данные')}}</div>
    <div class="panel-body panel-profile">
        <form type="POST" class="ajax-form ajax2" action="{{route('change_user_info')}}">
            @csrf
            <ul class="row login-sec">
                <li class="col-sm-12">
                    <label>{{ __('ФИО') }}
                        <input type="text" class="form-control" name="fio" value="{{ Auth::user()->fio }}" required>
                    </label>
                </li>
                <li class="col-sm-12">
                    <label>{{__('Адрес електронной почты')}}
                        @if(isset(Auth::user()->email_verified_at))
                            <i class="fa fa-check text-success" aria-hidden="true" title="Подтверждён"></i>
                        @else
                            <a class="text-danger" href="{{ route('verification.resend') }}">
                                <span class="small">(нажмите здесь для повторной отапрвки письма)</span>
                            </a>
                        @endif
                        <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" required>
                    </label>
                </li>
                <li class="col-sm-12">
                    <label>{{__('Телефон')}}
                        <input type="tel" class="phone_mask form-control" name="phone" value="{{ Auth::user()->phone }}" required>
                    </label>
                    <hr class="margin-0">
                    <button data-toggle="modal" data-target="#add_user_phone_modal" type="button" class="add-car margin-bottom-5">
                        <span aria-hidden="true">{{__('+добавить телефон')}}</span>
                    </button>
                    @isset($user_phones)
                        <p>{{__('Дополнительные телофоны')}}</p>
                        <ul class="list-group" id="list_user_phone">
                            @foreach($user_phones as $item)
                                <li id="phone_{{$item->id}}" class="list-group-item">
                                    <span onclick="deletePhone('{{$item->id}}')" class="badge"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
                                    {{$item->phone}}
                                </li>
                            @endforeach
                        </ul>
                    @endisset
                </li>
                <li class="col-sm-12">
                    <label>{{__('Тип клиента')}}
                        <select class="form-control" name="role" required>
                            @isset($roles)
                                @foreach($roles as $role)
                                    <option @if($role->id == Auth::user()->role) selected @endif value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach
                            @endisset
                        </select>
                    </label>
                </li>
                <li class="col-sm-12 text-left">
                    <button type="submit" class="btn-round">{{__('Сохранить')}}</button>
                </li>
            </ul>
        </form>
    </div>
</div>
<script>
    function deletePhone(id) {
        $.get(`{{route('dop_user_phone')}}?del_phone=${id}`,function (data) {
            alert(data.response);
            $(`#phone_${id}`).remove();
        });
    }
</script>
