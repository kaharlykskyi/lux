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

<div class="row form-group">
    <div class="col col-md-3">
        <label for="text-input" class=" form-control-label">{{__('Название')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input type="text" id="text-input" name="title" value="{{isset($top_menu->id)?$top_menu->title:''}}" class="form-control" required>
    </div>
</div>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="show_menu" class=" form-control-label">{{__('Показывать в меню')}}</label>
    </div>
    <div class="col-12 col-md-9">
        <input style="width: 20px;height: 20px;" type="checkbox" @if(isset($top_menu->id) && $top_menu->show_menu === 1) checked @endif id="show_menu" value="1"  name="show_menu" class="form-control-file">
    </div>
</div>

<hr>

<div class="row form-group">
    <div class="col col-md-3">
        <label for="key_words" class=" form-control-label">{{__('Дочерние категории')}}</label><br>
        <span class="small text-info">Перетащите в право неообходимые категории</span>
    </div>
    <div class="col-12 col-md-9">
        @php
            if (isset($top_menu->id) && isset($top_menu->tecdoc_category)){
                $use_category = json_decode($top_menu->tecdoc_category);
            }else{
                $use_category = [];
            }
        @endphp
        <ul style="height: 370px;overflow: auto;" id="sortable1" class="connectedSortable">
            @if(isset($all_category) && !empty($all_category))
                @foreach($all_category as $category)
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

<input type="hidden" id="categories" name="tecdoc_category" value="@isset($top_menu->id){{$top_menu->tecdoc_category}}@endisset">

<div class="row form-group">
    <div class="col col-md-12">
        <button onclick="submitForm()" type="button" class="btn btn-primary btn-sm">
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

        function submitForm() {
            const use_category = $('#sortable2 > li');
            let use_category_arr = [];

            for (let item = 0;item < use_category.length;item++){
                use_category_arr.push($(use_category[item]).attr('date-id'));
            }

            $('#categories').val(JSON.stringify(use_category_arr));
            $('#top_menu_form').submit();
        }
    </script>
@endsection
