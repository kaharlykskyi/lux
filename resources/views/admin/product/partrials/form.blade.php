<div class="row form-group">
    <div class="col col-md-3">
        <label for="name" class=" form-control-label">{{__('Название товара')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="name" name="name" value="@if(isset($product->id)){{$product->name}}@else{{old('name')}}@endif" class="form-control" required>
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
        <input type="text" id="company" name="company" value="@if(isset($product->id)){{$product->company}}@else{{old('company')}}@endif" required class="form-control">
        @if ($errors->has('company'))
            <small class="form-text text-danger">{{ $errors->first('company') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="articles" class=" form-control-label">{{__('Артикль')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="company" name="articles" value="@if(isset($product->id)){{$product->articles}}@else{{old('articles')}}@endif" required class="form-control">
        @if ($errors->has('articles'))
            <small class="form-text text-danger">{{ $errors->first('articles') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="brand" class=" form-control-label">{{__('Бренд')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="company" name="brand" value="@if(isset($product->id)){{$product->brand}}@else{{old('brand')}}@endif" required class="form-control">
        @if ($errors->has('brand'))
            <small class="form-text text-danger">{{ $errors->first('brand') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="short_description" class=" form-control-label">{{__('Краткое описание')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="short_description" name="short_description" value="@if(isset($product->id)){{$product->short_description}}@else{{old('short_description')}}@endif" class="form-control">
        @if ($errors->has('short_description'))
            <small class="form-text text-danger">{{ $errors->first('short_description') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="full_description" class=" form-control-label">{{__('Полное описание')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="full_description" name="full_description" value="@if(isset($product->id)){{$product->full_description}}@else{{old('full_description')}}@endif" class="form-control">
        @if ($errors->has('full_description'))
            <small class="form-text text-danger">{{ $errors->first('full_description') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="count" class=" form-control-label">{{__('Остаток на складе')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="count" name="count" value="@if(isset($product->id)){{$product->count}}@else{{old('count')}}@endif" class="form-control">
        @if ($errors->has('count'))
            <small class="form-text text-danger">{{ $errors->first('count') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="price" class=" form-control-label">{{__('Цена')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="number" onblur="toFloat($(this))" id="price" name="price" value="@if(isset($product->id)){{$product->price}}@else{{old('price')}}@endif" required placeholder="0.00" class="form-control">
        @if ($errors->has('price'))
            <small class="form-text text-danger">{{ $errors->first('price') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="old_price" class=" form-control-label">{{__('Старая цена')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="number" onblur="toFloat($(this))" id="old_price" name="old_price" value="@if(isset($product->id)){{$product->old_price}}@else{{old('old_price')}}@endif" placeholder="0.00" class="form-control">
        @if ($errors->has('old_price'))
            <small class="form-text text-danger">{{ $errors->first('old_price') }}</small>
        @endif
    </div>
</div>

<script>
    const toFloat = (obj) => {
        let val = $(obj).val();
        val = parseFloat(val).toFixed(2);
        $(obj).val(val);
    };
</script>

<button type="submit" class="btn btn-primary btn-sm">
    <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
</button>