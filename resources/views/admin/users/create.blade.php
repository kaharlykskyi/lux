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
            <div class="col-12">
                <div class="card m-t-15">
                    <div class="card-header">
                        <strong class="card-title mb-3">Добавление нового пользователя</strong>
                    </div>
                    <div class="card-body card-block">
                        <form action="{{route('admin.user.create')}}" method="post" novalidate="novalidate">
                            @csrf
                            <div class="form-group">
                                <label for="name" class="control-label mb-1">Имя</label>
                                <input id="name" type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                         <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group has-success">
                                <label for="sername" class="control-label mb-1">Фамилия</label>
                                <input id="sername" type="text" class="form-control {{ $errors->has('sername') ? ' is-invalid' : '' }}" name="sername" value="{{ old('sername') }}" required>
                                @if ($errors->has('sername'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('sername') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="last_name" class="control-label mb-1">Отчество</label>
                                <input id="last_name" type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" required>
                                @if ($errors->has('last_name'))
                                    <span class="invalid-feedback">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="email" class="control-label mb-1">Адрес електронной почты</label>
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="phone" class="control-label mb-1">Телефон</label>
                                <input id="phone" type="tel" class="phone_mask form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone') }}" required>
                                @if ($errors->has('phone'))
                                    <span class="invalid-feedback">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="country" class="control-label mb-1">Страна</label>
                                <input id="country" type="text" class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" value="{{ old('country') }}" required>
                                @if ($errors->has('country'))
                                    <span class="invalid-feedback">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="city" class="control-label mb-1">Город</label>
                                <input id="city" type="text" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ old('city') }}" required>
                                @if ($errors->has('city'))
                                    <span class="invalid-feedback">
                                            <strong>{{ $errors->first('city') }}</strong>
                                        </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="role" class="control-label mb-1">Тип клиента</label>
                                <select id="role" class="form-control" name="role" required>
                                    @isset($roles)
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="password" class="control-label mb-1">Пароль</label>
                                <input id="password" type="text" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                @endif
                            </div>
                            <div>
                                <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">Добавить пользователя</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @component('admin.component.footer')@endcomponent
    </div>

@endsection
