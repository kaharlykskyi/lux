<div class="row form-group">
    <div class="col col-md-3">
        <label for="title" class=" form-control-label">{{__('Название')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="name" name="name" value="@if(isset($provider->id)){{$provider->name}}@else{{old('name')}}@endif" placeholder="{{__('Название поставщика...')}}" class="form-control" required>
        @if ($errors->has('name'))
            <small class="form-text text-danger">{{ $errors->first('name') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="alias" class=" form-control-label">{{__('E-mail')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="email" name="email" value="@if(isset($provider->id)){{$provider->email}}@else{{old('email')}}@endif" placeholder="{{__('Електронная почта поставщика')}}" class="form-control">
        @if ($errors->has('email'))
            <small class="form-text text-danger">{{ $errors->first('email') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="description" class=" form-control-label">{{__('Телефон')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="phone" name="phone" value="@if(isset($provider->id)){{$provider->phone}}@else{{old('phone')}}@endif" placeholder="{{__('Мобильный телефон поставщика')}}" class="form-control">
        @if ($errors->has('phone'))
            <small class="form-text text-danger">{{ $errors->first('phone') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="desc" class=" form-control-label">{{__('Описание')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <textarea id="desc" name="desc" rows="4" class="form-control">@if(isset($provider->id)){{$provider->content}}@else{{old('content')}}@endif</textarea>
    </div>
</div>
<div class="row form-group">
    <div class="col col-md-3">
        <label for="footer_column" class=" form-control-label">{{__('Валюта')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <select name="currency" id="currency" class="form-control" required>
            <option @isset($provider->id) @if($provider->currency === 'UAH') selected @endif @endisset value="UAH">{{__('UAH')}}</option>
            <option @isset($provider->id) @if($provider->currency === 'EUR') selected @endif @endisset value="EUR">{{__('EUR')}}</option>
            <option @isset($provider->id) @if($provider->currency === 'USD') selected @endif @endisset value="USD">{{__('USD')}}</option>
        </select>
    </div>
</div>

<button type="submit" class="btn btn-primary btn-sm">
    <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
</button>
