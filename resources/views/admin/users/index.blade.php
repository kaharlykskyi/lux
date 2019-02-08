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
                            <select class="js-select2 au-select-dark" name="time">
                                <option selected="selected">All Time</option>
                                <option value="">By Month</option>
                                <option value="">By Day</option>
                            </select>
                            <div class="dropDownSelect2"></div>
                        </div>
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
                                                    <select data-user="{{$user->id}}" class="js-select2" name="permission" id="permission">
                                                        <option value="admin" @if($user->permission === 'admin') selected @endif>{{__('админ')}}</option>
                                                        <option value="manager" @if($user->permission === 'manager') selected @endif>{{__('модератор')}}</option>
                                                        <option value="user" @if($user->permission === 'user') selected @endif>{{__('пользователь')}}</option>
                                                        <option value="block" @if($user->permission === 'block') selected @endif>{{__('заблокирован')}}</option>
                                                    </select>
                                                    <div class="dropDownSelect2"></div>
                                                </div>
                                                <script>
                                                    $(document).ready(function () {

                                                        $('#permission').change(function () {
                                                            $.post('{{route('permission')}}',{
                                                                'permission' : $(this).val(),
                                                                'id': $(this).attr('data-user'),
                                                                '_token': '{{ csrf_token() }}'
                                                            },function (data) {
                                                                alert(data.response);
                                                            });
                                                        });
                                                    });
                                                </script>
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

@endsection