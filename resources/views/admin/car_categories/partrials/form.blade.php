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

<input type="hidden" name="categories" id="categories" value="{{isset($car_categories->id)?$car_categories->categories:''}}">

<div class="row form-group">
    <div class="col col-md-3">
        <label for="title" class=" form-control-label">{{__('Название')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="title" name="title" value="{{isset($car_categories->id)?$car_categories->title:old('title')}}" class="form-control">
        @if ($errors->has('title'))
            <small class="form-text text-danger">{{ $errors->first('title') }}</small>
        @endif
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="logo" class=" form-control-label">{{__('Картинка категории')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="file" id="logo" name="logo" class="form-control-file">
        <img style="max-width: 100px;" class="m-t-15" src="@if(isset($car_categories->id)) {{asset('images/catalog/' . $car_categories->logo)}} @else {{asset('images/map-locator.png')}} @endif" alt="">
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="key_words" class=" form-control-label">{{__('Дочерние категории')}}</label><br>
        <span class="small text-info">Перетащите в право неообходимые категории</span>
    </div>
    <div class="col-12 col-md-9">
        @php
            if (isset($car_categories->id)){
                $use_category = json_decode($car_categories->categories);
            }else{
                $use_category = [];
            }
        @endphp
        <ul id="sortable1" class="connectedSortable">
            @if(isset($all_car_category) && !empty($all_car_category))
                @foreach($all_car_category as $category)
                    @if(isset($use_category))
                        @if(!in_array($category->id,$use_category))
                            <li date-id="{{$category->id}}" class="ui-state-default">{{$category->name}}</li>
                        @endif
                    @else
                        <li date-id="{{$category->id}}" class="ui-state-default">{{$category->name}}</li>
                    @endif
                @endforeach
            @endif
        </ul>

        <ul id="sortable2" class="connectedSortable">
            @if(isset($all_car_category) && !empty($all_car_category))
                @foreach($all_car_category as $item)
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

            $('#categories').val(use_category_str);
        }
    </script>
@endsection
