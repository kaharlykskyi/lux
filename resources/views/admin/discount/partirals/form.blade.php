<div class="row form-group">
    <div class="col col-md-3">
        <label for="title" class=" form-control-label">{{__('Процент скидки')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="percent" name="percent" value="@if(isset($discount->id)){{$discount->percent}}@else{{old('percent')}}@endif" class="form-control" required>
        <small class="form-text text-info">{{__('Только цифру')}}</small>
    @if ($errors->has('percent'))
            <small class="form-text text-danger">{{ $errors->first('percent') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="description" class=" form-control-label">{{__('Описание')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="description" name="description" value="@if(isset($discount->id)){{$discount->description}}@else{{old('description')}}@endif" class="form-control">
        @if ($errors->has('description'))
            <small class="form-text text-danger">{{ $errors->first('description') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="count_buy" class=" form-control-label">{{__('Количество покупок')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="count_buy" name="count_buy" value="@if(isset($discount->id)){{$discount->count_buy}}@else{{old('count_buy')}}@endif" class="form-control">
        <small class="form-text text-info">{{__('Заполнять если необходимо автоматически активировать скидку после определенного количества покупок')}}</small>
        @if ($errors->has('count_buy'))
            <small class="form-text text-danger">{{ $errors->first('count_buy') }}</small>
        @endif
    </div>
</div>

<button type="submit" class="btn btn-primary btn-sm">
    <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
</button>
