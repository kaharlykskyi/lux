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
        <label for="provider_id" class=" form-control-label">{{__('Название компании')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <select name="provider_id" id="provider_id" class="form-control">
            @foreach($providers as $provider)
                <option @isset($product->id) @if($product->provider_id == $provider->id) selected @endif @endisset value="{{$provider->id}}">{{$provider->name}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="articles" class=" form-control-label">{{__('Артикль')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="articles" name="articles" value="@if(isset($product->id)){{$product->articles}}@else{{old('articles')}}@endif" required class="form-control">
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
        <input type="text" id="brand" name="brand" value="@if(isset($product->id)){{$product->brand}}@else{{old('brand')}}@endif" required class="form-control">
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

<div class="row form-group">
    <div class="col col-md-3">
        <label for="provider_price" class=" form-control-label">{{__('Цена поставщика')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="number" onblur="toFloat($(this))" id="provider_price" name="provider_price" value="@if(isset($product->id)){{$product->provider_price}}@else{{old('provider_price')}}@endif" placeholder="0.00" class="form-control">
        @if ($errors->has('provider_price'))
            <small class="form-text text-danger">{{ $errors->first('provider_price') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="count" class=" form-control-label">{{__('Валюта поставщика')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="provider_currency" name="provider_currency" value="@if(isset($product->id)){{$product->provider_currency}}@else{{old('provider_currency')}}@endif" class="form-control">
        @if ($errors->has('provider_currency'))
            <small class="form-text text-danger">{{ $errors->first('provider_currency') }}</small>
        @endif
    </div>
</div>

@isset($product->stocks)
    <hr>
    <p class="h5">Запасы по складам</p>
    @php $stocks_decode = json_decode($product->stocks);@endphp
    <ul class="list-group m-b-15">
        @foreach($stocks_decode as $k => $stocks)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{$k}}
                <span class="badge badge-primary badge-pill">{{$stocks}}</span>
            </li>
        @endforeach
    </ul>
@endisset

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
