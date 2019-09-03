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
        #droppable{
            position: absolute;
            right: 0;
            z-index: 1;
            height: 60px;
            display: flex;
            justify-content: center;
            align-items: center;
            top: -60px;
            left: 0;
            background: transparent;
            border: none;
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
        <label for="parent_id" class=" form-control-label">{{__('Главная категория')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <select name="parent_id" id="parent_id" class="form-control">
            <option value="0">Главная</option>
            @foreach($root_car_category as $category)
                <option @if(isset($car_categories->id) && $car_categories->parent_id === $category->id) selected @endif value="{{$category->id}}">{{$category->title}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="range" class=" form-control-label">{{__('Позиция в меню')}}</label>
        <small class="form-text text-info">не отрицательные числа</small>
    </div>
    <div class="col-12 col-md-9">
        <input type="number" id="range" name="range" value="{{isset($car_categories->id)?$car_categories->range:old('range')}}" class="form-control">
        @if ($errors->has('range'))
            <small class="form-text text-danger">{{ $errors->first('range') }}</small>
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
        <span class="small text-info">Выбирите главную категорию категорию, а потом перетащите в право неообходимые категории</span>
    </div>
    <div class="col-12 col-md-9">
        <div id="droppable" class="ui-widget-header">
            <p><i class="fa fa-trash" aria-hidden="true"></i></p>
        </div>
        @php
            if (isset($car_categories->id) && isset($car_categories->categories)){
                $use_category = json_decode($car_categories->categories);
            }else{
                $use_category = [];
            }
        @endphp
        <ul class="list-group" id="root_tecdoc_cat" style="height: 370px;overflow: auto;width: 50%;float: left;">
            @foreach ($root_all_category as $category)
                <li style="padding-left: 5px !important;cursor: pointer" onclick="getChild({{$category->id}})" class="list-group-item d-flex justify-content-between align-items-center p-0">
                    {{$category->name}}
                </li>
            @endforeach
        </ul>
        <ul style="height: 370px;overflow: auto;display: none" id="sortable1" class="connectedSortable"></ul>

        <ul id="sortable2" class="connectedSortable">
            @if(isset($all_category) && !empty($all_category))
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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <script>
        $( function() {
            $( "#sortable1, #sortable2" ).sortable({
                connectWith: ".connectedSortable"
            }).disableSelection();

            $( "#droppable" ).droppable({
                drop: function( event, ui ) {
                    console.log(ui.draggable)
                    $(ui.draggable).remove()
                    $( this )
                        .removeClass( "ui-state-highlight" )
                },
                over: function( event, ui ) {
                    $( this )
                        .addClass( "ui-state-highlight" )
                },
                out: function( event, ui ) {
                    $( this )
                        .removeClass( "ui-state-highlight" )
                }
            });
        } );
        function setCategoryId() {
            const use_category = $('#sortable2 > li');
            let use_category_str = '';
            const useTecDocIds = [];

            for (let item = 0;item < use_category.length;item++){
                const id = $(use_category[item]).attr('date-id');
                if(!useTecDocIds.includes(id)){
                    useTecDocIds.push(id);
                    use_category_str += `${id}@`;
                }
            }

            $('#categories').val(use_category_str);
        }
        function getChild(id) {
            $('#root_tecdoc_cat').hide();
            $('#sortable1').html('<li class="ui-state-default">загрузка...</li>').show()
            $.get(`{{route('admin.tecdoc.child_category')}}?id=${id}`,function (data) {
                let html = '';
                const useTecDocIds = [];
                data.forEach(function (item) {
                    const used = false;
                    useTecDocIds.forEach(item => {
                        if(item === item.tecdoc_id){
                            return true;
                        }
                    });
                    if(!used){
                        useTecDocIds.push(item.tecdoc_id);
                        html += `<li date-id="${item.id}" class="ui-state-default">${item.name}</li>`
                    }
                })
                $('#sortable1').html(html);
            })
        }
    </script>
@endsection
