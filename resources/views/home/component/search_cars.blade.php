<div class="modal fade" tabindex="-1" role="dialog" id="search_cars_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{__('Мой гараж')}}</h4>
            </div>
            <div class="modal-body">
                @if(isset($search_cars))
                    @foreach($search_cars as $k => $item)
                        <div class="list-group relative" id="list-group{{$k}}">
                            <button class="delete-car-btn" onclick="deleteCarModal('{{$item['cookie']['modification_auto']}}',{{$k}})" title="{{__('Удалить машину')}}">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="list-group-item" onclick="getCarsDetail('{{$item['cookie']['type_auto']}}','{{$item['cookie']['year_auto']}}','{{$item['cookie']['brand_auto']}}','{{$item['cookie']['model_auto']}}','{{$item['cookie']['modification_auto']}}','{{$item['cookie']['engine_auto']}}','{{$item['cookie']['body_auto']}}','{{csrf_token()}}','{{$item['data'][0]->name}}','{{$item['data'][0]->displayvalue}}')">
                                {{$item['data'][0]->name}}
                                <br><span class="small">{{$item['data'][0]->displayvalue}}</span>
                            </button>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-warning" role="alert">{{__('В данный момент ваш гараж пуст. Пожалуйста, выберите авто для добавления')}}</div>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    function deleteCarModal(modification,id) {
        $.get(`{{route('del_garage_car')}}?mod=${modification}`,function () {
            $(`#list-group${id}`).remove();
        });
    }
</script>
