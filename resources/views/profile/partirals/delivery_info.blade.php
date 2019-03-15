<div class="panel panel-primary">
    <div class="panel-heading">{{__('Информация о доставке')}}</div>
    <div class="panel-body panel-profile">
        <form type="POST" action="{{route('change_delivery_info')}}" class="ajax-form ajax2">
            @csrf
            <ul class="row login-sec">
                <li class="col-sm-12">
                    <label class="relative country">{{__('Страна')}}
                        <input id="delivery_country" oninput="getCountry($(this),'#delivery_country')" type="text" class="form-control" name="delivery_country" value="@isset($delivery_info){{ $delivery_info->delivery_country }}@endisset" autocomplete="off">
                        <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                    </label>
                </li>
                <li class="col-sm-12">
                    <label class="relative city">{{__('Город')}}
                        <input id="delivery_city" oninput="getCity($(this),'#delivery_country')" type="text" class="form-control" name="delivery_city" value="@isset($delivery_info){{ $delivery_info->delivery_city }}@endisset" autocomplete="off">
                        <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                    </label>
                </li>

                <li class="col-sm-12">
                    <label>{{ __('Улица') }}
                        <input type="text" class="form-control" name="street" value="@isset($delivery_info){{ $delivery_info->street }}@endisset">
                    </label>
                </li>

                <li class="col-sm-12">
                    <label>{{ __('Дом') }}
                        <input type="text" class="form-control" name="house" value="@isset($delivery_info){{ $delivery_info->house }}@endisset">
                    </label>
                </li>

                <li class="col-sm-12">
                    <label>{{ __('Контактный телефон') }}
                        <input type="text" class="form-control" name="phone" value="@isset($delivery_info){{ $delivery_info->phone }}@endisset">
                    </label>
                </li>

                <li class="col-sm-12">
                    <label>{{__('Служба доставки')}}
                        <select class="form-control" name="delivery_service" id="delivery_service">
                            <option @isset($delivery_info) @if($delivery_info->delivery_service === 'novaposhta') selected @endif @endisset value="{{__('novaposhta')}}">{{__('Новая Почта')}}</option>
                        </select>
                    </label>
                </li>

                <li class="col-sm-12 delivery-dep" style="display:none;">
                    <label class="relative delivery-department">{{ __('Номер отделения') }}
                        <input type="text" class="form-control" id="delivery_department" name="delivery_department" value="@isset($delivery_info){{ $delivery_info->delivery_department }}@endisset" autocomplete="off">
                        <span class="loader"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></span>
                    </label>
                </li>

                <li class="col-sm-12">
                    <script src="{{asset('js/map.js')}}"></script>
                    <div id="map" style="height: 200px;"></div>
                    <script type="text/javascript" async defer
                            src="https://maps.googleapis.com/maps/api/js?v=3&libraries=places&callback=initMap&key={{config('app.google_key')}}"></script>
                </li>

                <li class="col-sm-12 text-left">
                    <button type="submit" class="btn-round">{{__('Сохранить')}}</button>
                </li>
            </ul>
        </form>
    </div>
</div>