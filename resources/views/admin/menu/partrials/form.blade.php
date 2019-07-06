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

<div class="form-row m-b-15">
    <div class="col-4">
        <label for="search_category" class=" form-control-label">{{__('Поиск категории в TecDoc')}}</label>
        <input type="text" name="search_category" id="search_category" class="form-control">
        <ul class="list-group m-t-10" id="show_res" style="display: none;height: 300px;overflow: auto;"></ul>
    </div>
    <div class="col-6">
        <label for="search_category" class=" form-control-label">{{__('Добавленые категории')}}</label>
        <input type="hidden" id="tecdoc_category_input" name="tecdoc_category" value="@if(isset($top_menu->id)){{$top_menu->tecdoc_category}}@endif">
        <ul class="list-group m-t-10" id="save_category" style="font-size: 13px">
            @isset($top_menu->id)
                @php $save_category = json_decode($top_menu->tecdoc_category) @endphp
                @foreach($save_category as $category)
                    <li id="category_{{$category->id}}" data-id="{{$category->id}}" class="list-group-item">
                        {{$category->name}}
                        <input style="border: 1px solid;padding: 0 5px;margin-left: 15px;" type="text" value="{{isset($category->custom_name)?$category->custom_name:$category->name}}">
                        <span style="cursor: pointer;" onclick="deleteItem({{$category->id}})" class="badge badge-primary badge-pill"><i class="fa fa-trash" aria-hidden="true"></i></span>
                    </li>
                @endforeach
            @endisset
        </ul>
    </div>
    <div class="col-2">

    </div>
</div>

<div class="row form-group">
    <div class="col col-md-12">
        <button onclick="submitForm()" type="button" class="btn btn-primary btn-sm">
            <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
        </button>
    </div>
</div>

<script>
    $('#search_category').on('input',function () {
        const str = $(this).val().trim();
        if (str.length > 3){
            $('#show_res').html('<li class="list-group-item"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i>\n</li>').show();
            $.get('{{route('admin.top_menu.tecdoc_category')}}?category=' + str, function (data) {
                let html = '';
                data.forEach(function (item) {
                    html += `<li onclick="add('${item.id}','${item.usagedescription.length > 0?item.normalizeddescription + ' - ' + item.usagedescription:item.normalizeddescription}')" class="list-group-item" style="cursor: pointer;">
                                ${item.usagedescription.length > 0?item.normalizeddescription + ' - ' + item.usagedescription:item.normalizeddescription}
                             </li>`;
                });

                $('#show_res').html(html);
            });
        }
    });

    function add(id,name) {
        $('#save_category').append(`
                            <li id="category_${id}" data-id="${id}" class="list-group-item">
                                ${name}
                                <input style="border: 1px solid;padding: 0 5px;margin-left: 15px;" type="text" value="${name}">
                                <span style="cursor: pointer;" onclick="deleteItem(${id})" class="badge badge-primary badge-pill"><i class="fa fa-trash" aria-hidden="true"></i></span>
                            </li>
                        `);
    }

    function deleteItem(id) {
        $('#category_' + id).remove();
    }

    function submitForm() {
        const items = $('#save_category li');
        let data = [];

        for (let i = 0;i < items.length; i++){
            data.push({
                'id': $(items[i]).attr('data-id'),
                'name': $(items[i]).text().trim(),
                'custom_name' : $(items[i]).find('input').val().trim()
            });
        }

        $('#tecdoc_category_input').val(JSON.stringify(data));
        $('#top_menu_form').submit();
    }

</script>