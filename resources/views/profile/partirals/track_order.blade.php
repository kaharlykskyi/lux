<div class="panel panel-primary">
    <div class="panel-heading">{{__('Личные данные')}}</div>
    <div class="panel-body panel-profile">
        <form type="get" id="profile_track_order" action="{{route('profile.track_order')}}">
            <ul class="row login-sec">
                <li class="col-sm-12">
                    <label>{{ __('ID заказа') }}
                        <input type="text" id="profile_track_id_order" class="form-control" name="id_order" value="" required>
                    </label>
                </li>
                <li class="col-sm-12 text-left">
                    <button type="submit" class="btn-round">{{__('Отследить')}}</button>
                </li>
            </ul>
        </form>
        <div class="panel panel-default hidden" id="result_track_order">
            <div class="panel-body"></div>
        </div>
    </div>
</div>