@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        #sortable1, #sortable2 {
            border: 1px solid #eee;
            width: 48%;
            min-height: 20px;
            list-style-type: none;
            margin: 0;
            padding: 5px 0 0 0;
            float: left;
            margin-right: 10px;
        }
        #sortable1 li, #sortable2 li {
            margin: 2px;
            padding: 0;
            font-size: 1rem;
            width: 99%;
        }
    </style>
@endsection

<input type="hidden" name="categories_id" id="categories_id" value="{{isset($homeCategoryGroup->id)?$homeCategoryGroup->categories_id:''}}">

<div class="row form-group">
    <div class="col col-md-3">
        <label for="text-input" class=" form-control-label">{{__('Название')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="text-input" name="name" value="{{isset($homeCategoryGroup->id)?$homeCategoryGroup->name:old('name')}}" class="form-control">
        @if ($errors->has('name'))
            <small class="form-text text-danger">{{ $errors->first('name') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="background" class=" form-control-label">{{__('Цвет заливки фона')}}</label>
        <span class="small text-info">Хекс формат или англю название цвета</span>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="background" name="background" value="{{isset($homeCategoryGroup->id)?$homeCategoryGroup->background:old('background')}}" class="form-control">
        @if ($errors->has('background'))
            <small class="form-text text-danger">{{ $errors->first('background') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="hurl" class=" form-control-label">{{__('Синоним для дружественого урла')}}</label>
    </div>
    <div class="col-12 col-md-9">
        @if(!isset($homeCategoryGroup->id))
            <input type="text" id="hurl" name="hurl" value="{{old('hurl')}}" class="form-control">
            @if ($errors->has('hurl'))
                <small class="form-text text-danger">{{ $errors->first('hurl') }}</small>
            @endif
            <small class="form-text text-info">Должно быть уникальным.Если не заполнить то сгенерируеться автоматически: имя транслитом</small>
        @else
            <p class="h5">{{$homeCategoryGroup->hurl}}</p>
        @endif
    </div>
</div>
<div class="row form-group">
    <div class="col col-md-3">
        <label for="key_words" class=" form-control-label">{{__('Краткое описание')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <textarea id="key_words" name="key_words" rows="9" class="form-control">@if(isset($homeCategoryGroup->id)){{$homeCategoryGroup->key_words}}@else{{old('key_words')}}@endif</textarea>
    </div>
</div>
<div class="row form-group">
    <div class="col col-md-3">
        <label for="file-input" class=" form-control-label">{{__('Картинка категории')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="file" id="file-input" name="logo" class="form-control-file">
        <img style="max-width: 100px;" class="m-t-15" src="@if(isset($homeCategoryGroup->id)) {{asset('images/catalog/' . $homeCategoryGroup->img)}} @else {{asset('images/map-locator.png')}} @endif" alt="">
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="key_words" class=" form-control-label">{{__('Дочерние категории')}}</label><br>
        <span class="small text-info">Перетащите в право неообходимые категории</span>
    </div>
    <div class="col-12 col-md-9">
        @php
            if (isset($homeCategoryGroup->id)){
                $use_category = explode(',',$homeCategoryGroup->categories_id);
            }else{
                $use_category = null;
            }
        @endphp
        <ul id="sortable1" class="connectedSortable">
            @isset($all_category)
                @foreach($all_category as $category)
                    @if(isset($use_category))
                        @if(!in_array($category->id,$use_category))
                            <li date-id="{{$category->id}}" class="ui-state-default">{{$category->name}}</li>
                        @endif
                    @else
                        <li date-id="{{$category->id}}" class="ui-state-default">{{$category->name}}</li>
                    @endif
                @endforeach
            @endisset
        </ul>

        <ul id="sortable2" class="connectedSortable">
            @if(isset($use_category) && isset($all_category))
                @foreach($all_category as $item)
                    @if(in_array($item->id,$use_category))
                        <li date-id="{{$item->id}}" class="ui-state-highlight">{{$item->name}}</li>
                    @endif
                @endforeach
            @endif
        </ul>
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-12">
        <button onclick="setCategoryId()" type="submit" class="btn btn-primary btn-sm">
            <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
        </button>
    </div>
</div>
@section('script')
    <script
        src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
        integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
        crossorigin="anonymous"></script>
    <script>
        $( function() {
            $( "#sortable1, #sortable2" ).sortable({
                connectWith: ".connectedSortable"
            }).disableSelection();
        } );
        function setCategoryId() {
            const use_category = $('#sortable2 > li');
            let use_category_str = '';

            for (let item = 0;item < use_category.length;item++){
                use_category_str += `${$(use_category[item]).attr('date-id')},`;
            }

            $('#categories_id').val(use_category_str);
        }
    </script>
@endsection
