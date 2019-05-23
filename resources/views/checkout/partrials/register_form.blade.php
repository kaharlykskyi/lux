<div class="login-sec">
    <form method="POST" action="{{ route('checkout.create_oder') }}">
        <input type="hidden" name="order_id" value="{{isset($cart->id)?$cart->id:''}}">
        @csrf

        <ul class="row">
            <li class="col-sm-12">
                <label>{{ __('Имя') }}
                    <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $user->name }}" required autofocus>
                </label>
                @if ($errors->has('name'))
                    <span class="invalid-feedback">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                @endif
            </li>
            <li class="col-sm-12">
                <label>{{__('Фамилия')}}
                    <input type="text" class="form-control {{ $errors->has('sername') ? ' is-invalid' : '' }}" name="sername" value="{{ $user->sername }}" required>
                </label>
                @if ($errors->has('sername'))
                    <span class="invalid-feedback">
                                            <strong>{{ $errors->first('sername') }}</strong>
                                        </span>
                @endif
            </li>
            <li class="col-sm-12">
                <label>{{__('Отчество')}}
                    <input type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ $user->last_name }}" required>
                </label>
                @if ($errors->has('last_name'))
                    <span class="invalid-feedback">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                @endif
            </li>
            <li class="col-sm-12">
                <label>{{__('Адрес електронной почты')}}
                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $user->email  }}" required>
                </label>
                @if ($errors->has('email'))
                    <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                @endif
            </li>
            <li class="col-sm-12">
                <label>{{__('Телефон')}}
                    <input type="tel" class="phone_mask form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ isset($user->deliveryInfo) ? $user->deliveryInfo->phone : $user->phone }}" required>
                </label>
                @if ($errors->has('phone'))
                    <span class="invalid-feedback">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                @endif
            </li>
            <li class="col-sm-12">
                <label class="relative country">{{__('Страна')}}
                    <input id="country" oninput="getCountry($(this))" type="text" class="form-control{{ $errors->has('country') ? ' is-invalid' : '' }}" name="country" value="{{ isset($user->deliveryInfo) ? $user->deliveryInfo->delivery_country : $user->country }}" required autocomplete="off">
                    <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                </label>
                @if ($errors->has('country'))
                    <span class="invalid-feedback">
                                            <strong>{{ $errors->first('country') }}</strong>
                                        </span>
                @endif
            </li>
            <li class="col-sm-12">
                <label class="relative city">{{__('Город')}}
                    <input id="city" oninput="getCity($(this),'#country')" type="text" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}" name="city" value="{{ isset($user->deliveryInfo) ? $user->deliveryInfo->delivery_city : $user->city }}" required autocomplete="off">
                    <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                </label>
                @if ($errors->has('city'))
                    <span class="invalid-feedback">
                                            <strong>{{ $errors->first('city') }}</strong>
                                        </span>
                @endif
            </li>
            <li class="col-sm-12">
                <label>{{__('Способы оплаты')}}
                    <select name="pay_method" id="pay_method" class="form-control">
                        <option value="receipt" selected >{{__('При получении')}}</option>
                        <option value="online" >{{__('Онлайн')}}</option>
                    </select>
                </label>
            </li>
            <li class="col-sm-12">
                <label>{{__('Доставка *')}}
                    <select id="delivery-service" name="delivery_service" class="form-control" required>
                        <option value="novaposhta" @if(isset($user->deliveryInfo)) @if($user->deliveryInfo->delivery_service === 'novaposhta') selected @endif @else selected @endif>{{__('Новая почта')}}</option>
                        <option value="samovivoz" @isset($user->deliveryInfo) @if($user->deliveryInfo->delivery_service === 'samovivoz') selected @endif @endisset>{{__('Самовывоз')}}</option>
                    </select>
                </label>
            </li>
            <li class="col-sm-12 delivery-dep" style="display: none">
                <label class="relative delivery-department">{{__('Отделение  *')}}
                    <input value="@isset($user->deliveryInfo) {{$user->deliveryInfo->delivery_department}} @endisset" id="delivery_department" type="text" class="form-control{{ $errors->has('delivery_department') ? ' is-invalid' : '' }}" name="delivery_department" autocomplete="off">
                    <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                </label>
                @if ($errors->has('delivery_department'))
                    <span class="invalid-feedback">
                                            <strong>{{ $errors->first('delivery_department') }}</strong>
                                        </span>
                @endif
            </li>
            <li class="col-sm-12">
                <script src="{{asset('js/map.js')}}"></script>
                <div id="map" style="height: 330px;"></div>
                <script type="text/javascript" async defer
                        src="https://maps.googleapis.com/maps/api/js?v=3&libraries=places&callback=initMap&key={{config('app.google_key')}}"></script>
            </li>
            <li class="col-sm-12 text-left">
                <button type="submit" class="btn-round">{{__('Сформировать заказ')}}</button>
            </li>
        </ul>
    </form>
</div>
