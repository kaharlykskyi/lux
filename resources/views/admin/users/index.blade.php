@extends('admin.layouts.admin')

@section('content')

    <div class="container-fluid m-t-75">
        @if (session('status'))
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-info" role="alert">
                        {{ session('status') }}
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-sm-12">
                <!-- USER DATA-->
                <div class="user-data m-b-30">
                    <h3 class="title-3 m-b-30">
                        <i class="zmdi zmdi-account-calendar"></i>{{__('Информация о пользователях')}}</h3>
                    <div class="filters m-b-45">
                        <div class="card">
                            <div class="card-body card-block">
                                <form action="{{route('admin.users')}}" method="get" id="filter_user">
                                    <div class="row form-group">
                                        <div class="col col-sm-4">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="user_fio" class=" form-control-label">ФИО</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <input type="text" id="user_fio" value="{{request()->query('user_fio')}}" name="user_fio" class="form-control">
                                                    <span class="small">Через пробел</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col-sm-4">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="user_phone" class=" form-control-label">Телефон</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <input type="text" id="user_phone" value="{{request()->query('user_phone')}}" name="user_phone" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col-sm-4">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="user_email" class="form-control-label">Email</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <input type="text" id="user_email" value="{{request()->query('user_email')}}" name="user_email" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <button onclick="$('#filter_user').submit();" class="btn btn-primary btn-sm">
                                    <i class="fa fa-dot-circle-o"></i> Фильтровать
                                </button>
                                <button onclick="location.href = '{{route('admin.users')}}'" class="btn btn-danger btn-sm">
                                    <i class="fa fa-ban"></i> Отменить
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-right p-r-30">
                            <button onclick="location.href = '{{route('admin.user.create')}}'" class="au-btn au-btn-icon au-btn--green au-btn--small">
                                <i class="zmdi zmdi-plus"></i>{{__('Создать пользователя')}}</button>
                        </div>
                    </div>
                    <div class="table-responsive table-data">
                        <table class="table">
                            <thead>
                            <tr>
                                <td>{{__('id')}}</td>
                                <td>{{__('Имя')}}</td>
                                <td>{{__('Роль')}}</td>
                                <td>{{__('Доступ')}}</td>
                                <td>{{__('Скидка')}}</td>
                                <td></td>
                            </tr>
                            </thead>
                            <tbody>
                                @isset($users)
                                    @forelse($users as $user)
                                        <tr>
                                            <td>{{$user->id}}</td>
                                            <td>
                                                <div class="table-data__info">
                                                    <h6>{{$user->name}}</h6>
                                                    <span>
                                                        <a href="#">{{$user->email}}</a>
                                                    </span><br>
                                                    <span class="small">{{$user->phone}}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="role member">
                                                    @isset($roles)
                                                        @foreach($roles as $role)
                                                            @if($role->id === $user->role)
                                                                {{$role->name}}
                                                            @endif
                                                        @endforeach
                                                    @endisset
                                                </span><br>
                                            </td>
                                            <td>
                                                <div class="rs-select2--trans rs-select2--sm">
                                                    <select onchange="setPermission('{{$user->id}}',this)" class="js-select2">
                                                        <option value="admin" @if($user->permission === 'admin') selected @endif>{{__('админ')}}</option>
                                                        <option value="manager" @if($user->permission === 'manager') selected @endif>{{__('модератор')}}</option>
                                                        <option value="user" @if($user->permission === 'user') selected @endif>{{__('пользователь')}}</option>
                                                        <option value="block" @if($user->permission === 'block') selected @endif>{{__('заблокирован')}}</option>
                                                    </select>
                                                    <div class="dropDownSelect2"></div>
                                                </div>
                                            </td>
                                            <td>
                                                @isset($discount)
                                                    <div class="rs-select2--trans rs-select2--sm">
                                                         <select onchange="setDiscount('{{$user->id}}',this)" class="js-select2">
                                                             <option value="null">{{__('скидки')}}</option>
                                                             @foreach($discount as $item)
                                                                 <option value="{{$item->id}}" @if($user->discount_id === $item->id) selected @endif>{{$item->percent . __('%')}}</option>
                                                             @endforeach
                                                         </select>
                                                        <div class="dropDownSelect2"></div>
                                                    </div>
                                                @endisset
                                            </td>
                                            <td class="font-size-12-440">
                                                <a href="{{route('admin.user.garage',$user->id)}}">
                                                    <i class="fa fa-car" aria-hidden="true"></i>
                                                    @if(isset($user->cars) && $user->cars->count() > 0)
                                                        [<span class="text-danger">{{$user->cars->count()}}</span>]
                                                    @endif Гараж
                                                </a><br>
                                                <a href="{{route('admin.user.show',$user->id)}}">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    Редактировать
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <div class="alert alert-warning" role="alert">
                                                    @if (isset($search))
                                                        {{__('Нету с таким именем пользователей')}}
                                                    @else
                                                        {{__('Загерестрированых пользователей ещё нету')}}
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END USER DATA-->
            </div>
            <div class="col-sm-12">
                {{$users->links()}}
            </div>
        </div>

        @component('admin.component.footer')@endcomponent
    </div>

    <script>
        function setPermission(id,obj) {
            $.post('{{route('permission')}}',{
                'permission' : $(obj).val(),
                'user_id':id,
                '_token': '{{ csrf_token() }}'
            },function (data) {
                alert(data.response);
            });
        }

        function setDiscount(id,obj) {
            $.post('{{route('discount_user')}}/' + id,{
                'discount_id' : $(obj).val(),
                '_token': '{{ csrf_token() }}'
            },function (data) {
                alert(data.response);
            });
        }
    </script>

@endsection
