<div class="row form-group">
    <div class="col col-md-3">
        <label for="title" class=" form-control-label">{{__('Заголовок страници')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="title" name="title" value="@if(isset($page->id)){{$page->title}}@else{{old('title')}}@endif" placeholder="{{__('Заголовок страници...')}}" class="form-control" required>
        @if ($errors->has('title'))
            <small class="form-text text-danger">{{ $errors->first('title') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="alias" class=" form-control-label">{{__('Alias страници')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="alias" name="alias" value="@if(isset($page->id)){{$page->alias}}@else{{old('alias')}}@endif" placeholder="{{__('Псевдоним страници...')}}" class="form-control">
        @if ($errors->has('alias'))
            <small class="form-text text-danger">{{ $errors->first('alias') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="description" class=" form-control-label">{{__('Краткое описание')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="description" name="description" value="@if(isset($page->id)){{$page->description}}@else{{old('description')}}@endif" placeholder="{{__('Краткое описание для мета-тега...')}}" class="form-control">
        @if ($errors->has('description'))
            <small class="form-text text-danger">{{ $errors->first('description') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="content" class=" form-control-label">{{__('Контент страници')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <textarea id="content" name="content" rows="9" class="form-control">@if(isset($page->id)){{$page->content}}@else{{old('content')}}@endif</textarea>
    </div>
</div>
<div class="row form-group">
    <div class="col col-md-3">
        <label for="footer_column" class=" form-control-label">{{__('Колонка Футера')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <select name="footer_column" id="footer_column" class="form-control" required>
            <option @isset($page->id) @if((int)$page->footer_column === 1) selected @endif @endisset value="1">{{__('Колонка #1')}}</option>
            <option @isset($page->id) @if((int)$page->footer_column === 2) selected @endif @endisset value="2">{{__('Колонка #2')}}</option>
            <option @isset($page->id) @if((int)$page->footer_column === 3) selected @endif @endisset value="3">{{__('Колонка #3')}}</option>
        </select>
    </div>
</div>

<script>
    CKEDITOR.config.allowedContent = true;
    CKEDITOR.config.protectedSource.push( /<i[\s\S]*?\>/g );
    CKEDITOR.config.protectedSource.push( /<\/i[\s\S]*?\>/g );
    CKEDITOR.config.protectedSource.push( /<form[\s\S]*?\>/g );
    CKEDITOR.config.protectedSource.push( /<\/form[\s\S]*?\>/g );
    CKEDITOR.replace( 'content' );
</script>

<button type="submit" class="btn btn-primary btn-sm">
    <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
</button>