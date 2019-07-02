<div class="row form-group">
    <div class="col col-md-3">
        <label for="fio" class=" form-control-label">{{__('ФИО')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="fio" name="fio" value="@if(isset($sto_client->id)){{$sto_client->fio}}@else{{old('fio')}}@endif" class="form-control" required>
        @if ($errors->has('fio'))
            <small class="form-text text-danger">{{ $errors->first('fio') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="car_name" class=" form-control-label">{{__('Автомобиль')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="car_name" name="car_name" value="@if(isset($sto_client->id)){{$sto_client->car_name}}@else{{old('car_name')}}@endif" class="form-control">
        @if ($errors->has('car_name'))
            <small class="form-text text-danger">{{ $errors->first('car_name') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="num_auto" class=" form-control-label">{{__('Номер авто')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="num_auto" name="num_auto" value="@if(isset($sto_client->id)){{$sto_client->num_auto}}@else{{old('num_auto')}}@endif" class="form-control" required>
        @if ($errors->has('num_auto'))
            <small class="form-text text-danger">{{ $errors->first('num_auto') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="brand" class=" form-control-label">{{__('Авто (марка)')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="brand" name="brand" value="@if(isset($sto_client->id)){{$sto_client->brand}}@else{{old('num_auto')}}@endif" class="form-control" required>
        @if ($errors->has('brand'))
            <small class="form-text text-danger">{{ $errors->first('brand') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="mileage" class=" form-control-label">{{__('Пробег')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="mileage" name="mileage" value="@if(isset($sto_client->id)){{$sto_client->mileage}}@else{{old('mileage')}}@endif" class="form-control">
        @if ($errors->has('mileage'))
            <small class="form-text text-danger">{{ $errors->first('mileage') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="vin" class=" form-control-label">{{__('VIN код')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="vin" name="vin" value="@if(isset($sto_client->id)){{$sto_client->vin}}@else{{old('vin')}}@endif" class="form-control" required>
        @if ($errors->has('vin'))
            <small class="form-text text-danger">{{ $errors->first('vin') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="data" class=" form-control-label">{{__('Дата продажи')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="date" id="data" name="data" value="@if(isset($sto_client->id)){{date('Y-m-d',strtotime($sto_client->data))}}@else{{old('data')}}@endif" class="form-control">
        @if ($errors->has('data'))
            <small class="form-text text-danger">{{ $errors->first('data') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="acceptor" class=" form-control-label">{{__('Телефон')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="phone" name="phone" value="@if(isset($sto_client->id)){{$sto_client->phone}}@else{{old('phone')}}@endif" class="form-control">
        @if ($errors->has('phone'))
            <small class="form-text text-danger">{{ $errors->first('phone') }}</small>
        @endif
    </div>
</div>

<button type="button" onclick="submit_sto_client_form(this)" class="btn btn-primary btn-sm">
    <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
</button>

<script>
    function submit_sto_client_form(obj){
        $('#sto_client_form').submit();
        $(obj).attr('disabled','disabled')
    }
</script>
