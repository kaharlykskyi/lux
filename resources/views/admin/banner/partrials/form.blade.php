<div class="row form-group">
    <div class="col col-md-3">
        <label for="file-input" class=" form-control-label">{{__('Картинка слайда')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="file" id="file-input" name="img" class="form-control-file">
        @isset($banner->id)<img style="max-width: 100px;" class="m-t-15" src="{{asset('images/banner_img/' . $banner->img)}}" alt="">@endisset
        @if ($errors->has('img'))
            <small class="form-text text-danger">{{ $errors->first('img') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="link" class=" form-control-label">{{__('Ссылка')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="url" id="link" name="link" value="{{isset($banner->id)?$banner->link:old('link')}}" class="form-control">
        @if ($errors->has('link'))
            <small class="form-text text-danger">{{ $errors->first('link') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="str_link" class=" form-control-label">{{__('Текст ссылки')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="str_link" name="str_link" value="{{isset($banner->id)?$banner->str_link:old('str_link')}}" class="form-control">
        @if ($errors->has('str_link'))
            <small class="form-text text-danger">{{ $errors->first('str_link') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="text" class=" form-control-label">{{__('Контент слайда')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <textarea id="text" name="text" rows="9" class="form-control">@if(isset($banner->id)){!! $banner->text !!}@else{{old('text')}}@endif</textarea>
    </div>
</div>

<script>
    CKEDITOR.replace( 'text' );
</script>

<button type="submit" class="btn btn-primary btn-sm">
    <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
</button>