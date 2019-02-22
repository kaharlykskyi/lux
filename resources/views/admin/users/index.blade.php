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
                        <div class="rs-select2--dark rs-select2--sm rs-select2--border">
                            <form class="form-header" action="{{route('admin.users')}}" method="POST">
                                @csrf
                                <input class="au-input au-input--xl" type="text" name="search" placeholder="{{__('Поиск пользователей за ником')}}" />
                                <button class="au-btn--submit" type="submit">
                                    <i class="zmdi zmdi-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @isset ($search)
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="alert alert-primary" role="alert">
                                    {{ __('Поиск по слову - ') . $search }}
                                </div>
                            </div>
                        </div>
                    @endisset
                    <div class="table-responsive table-data">
                        <table class="table">
                            <thead>
                            <tr>
                                <td>{{__('Имя')}}</td>
                                <td>{{__('Роль')}}</td>
                                <td>{{__('Доступ')}}</td>
                                <td>{{__('Скидка')}}</td>
                            </tr>
                            </thead>
                            <tbody>
                                @isset($users)
                                    @forelse($users as $user)
                                        <tr>
                                            <td>
                                                <div class="table-data__info">
                                                    <h6>{{$user->name}}</h6>
                                                    <span>
                                                <a href="#">{{$user->email}}</a>
                                            </span>
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
                                                </span>
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
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">
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
            $.post('{{route('permission')}}/' + id,{
                'permission' : $(obj).val(),
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