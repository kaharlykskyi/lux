<div class="row form-group">
    <div class="col col-md-3">
        <label for="name" class=" form-control-label">{{__('Название склада')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="name" name="name" value="@if(isset($stock->id)){{$stock->name}}@else{{old('name')}}@endif" class="form-control" required>
        @if ($errors->has('name'))
            <small class="form-text text-danger">{{ $errors->first('name') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="company" class=" form-control-label">{{__('Название компании')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="company" name="company" value="@if(isset($stock->id)){{$stock->company}}@else{{old('company')}}@endif" required class="form-control">
        @if ($errors->has('company'))
            <small class="form-text text-danger">{{ $errors->first('company') }}</small>
        @endif
    </div>
</div>


<button type="submit" class="btn btn-primary btn-sm">
    <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
</button>