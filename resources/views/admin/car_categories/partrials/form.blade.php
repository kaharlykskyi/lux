@section('style')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

<input type="hidden" name="categories" id="categories" value="{{isset($car_categories->id)?$car_categories->categories:''}}">
<input type="hidden" name="root_child" id="root_child" value="">

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
    <div class="col-12">
        <label for="key_words" class=" form-control-label">{{__('Дочерние категории')}}</label><br>
        <span class="small text-info">Выбирите главную категорию категорию, а потом перетащите в право неообходимые категории</span>
    </div>
    <div class="col-12 m-t-60">
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
        <ul class="connectedSortable" id="sortable4">
            @foreach ($root_all_category as $category)
                @php
                    if (isset($car_categories->id) && $car_categories->childRootCategories->count() > 0){
                        foreach ($car_categories->childRootCategories as $item){
                            if ($item->id === $category->id) continue;
                        }
                    }
                @endphp
                <li data-id="{{$category->id}}" class="ui-state-default">
                    {{$category->name}}
                    <span onclick="getChild({{$category->id}})">дочерние</span>
                </li>
            @endforeach
        </ul>
        <ul style="display: none" id="sortable1" class="connectedSortable"></ul>
        <button type="button" id="showRootBtn" class="btn btn-success hidden" onclick="showRoot()" style="position: absolute;bottom: -10px;left: 0;">назад</button>

        <ul id="sortable3" class="connectedSortable">
            @if (isset($car_categories->id) && $car_categories->childRootCategories->count() > 0)
                @foreach ($car_categories->childRootCategories as $item)
                    <li date-id="{{$item->id}}" class="ui-state-highlight">{{$item->name}}</li>
                @endforeach
            @endif
        </ul>

        <ul style="display: none" id="sortable2" class="connectedSortable">
            @if(isset($all_category) && !empty($all_category))
                @php $uniq_category = []; @endphp
                @foreach($all_category as $item)
                    @if(in_array($item->id,$use_category) && !in_array($item->tecdoc_id,$uniq_category))
                        <li data-tecdoc="{{$item->tecdoc_id}}" date-id="{{$item->id}}" class="ui-state-highlight">{{$item->name}}</li>
                        @php $uniq_category[] = $item->tecdoc_id; @endphp
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
            $( "#sortable1, #sortable2,#sortable3,#sortable4" ).sortable({
                connectWith: ".connectedSortable"
            }).disableSelection();

            $( "#droppable" ).droppable({
                drop: function( event, ui ) {
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
            const useIds = [];
            const useTecDocIds = [];

            const use_root_category = $('#sortable3 > li');
            let use_root_category_str = '';
            const useRootIds = [];

            for (let item = 0;item < use_root_category.length;item++){
                const id = $(use_root_category[item]).attr('data-id');
                if(!useRootIds.includes(id)){
                    useRootIds.push(id);
                    use_root_category_str += `${id}@`;
                }
            }
            console.log(use_root_category_str)

            for (let item = 0;item < use_category.length;item++){
                const id = $(use_category[item]).attr('date-id');
                const tecdoc_id = $(use_category[item]).attr('data-tecdoc');
                if(!useIds.includes(id) && !useTecDocIds.includes(tecdoc_id)){
                    useTecDocIds.push(tecdoc_id);
                    useIds.push(id);
                    use_category_str += `${id}@`;
                }
            }

            $('#categories').val(use_category_str);
            $('#root_child').val(use_root_category_str);
        }
        function getChild(id) {
            const useTecDocIdsSave = JSON.parse('{{json_encode($uniq_category)}}');
            $('#sortable4,#sortable3').hide();
            $('#sortable2').show();
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
                    if(!used && !useTecDocIdsSave.includes(item.tecdoc_id)){
                        useTecDocIds.push(item.tecdoc_id);
                        html += `<li data-tecdoc="${item.tecdoc_id}" date-id="${item.id}" class="ui-state-default">${item.name}</li>`
                    }
                })
                $('#sortable1').html(html);
                $('#showRootBtn').toggleClass('hidden')
            })
        }

        function showRoot() {
            $('#showRootBtn').toggleClass('hidden');
            $('#sortable4,#sortable3').show();
            $('#sortable1,#sortable2').hide();
        }
    </script>
@endsection
