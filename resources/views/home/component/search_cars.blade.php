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
                            <button class="delete-car-btn" onclick="deleteCarModal('{{$item['cookie']['modification_auto']}}',{{$k}} @if($k === 0) ,true @endif )" title="{{__('Удалить машину')}}">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                            <button type="button" class="list-group-item" onclick="getCarsDetail('{{$item['cookie']['type_auto']}}','{{$item['cookie']['year_auto']}}','{{$item['cookie']['brand_auto']}}','{{$item['cookie']['model_auto']}}','{{$item['cookie']['modification_auto']}}','{{csrf_token()}}','{{$item['data'][0]->name}}','{{$item['data'][0]->displayvalue}}','{{route('modification_info')}}')">
                                {{$item['data'][0]->name}}
                                <br><span class="small">{{$item['data'][0]->displayvalue}}</span>
                                <img style="float:right;width: 100px;" src="https://yii.dbroker.com.ua/img/all_cars/{{$item['cookie']['model_auto']}}f.png" alt="{{$item['data'][0]->name}}">
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
    function deleteCarModal(modification,id,reload) {
        $.get(`{{route('del_garage_car')}}?mod=${modification}`,function () {
            $(`#list-group${id}`).remove();
            if (reload){
                location.reload()
            }
        });
    }

    @if(isset($search_cars) && !empty($search_cars))
        $(document).ready(function () {
        getCarsDetail('{{$search_cars[0]['cookie']['type_auto']}}','{{$search_cars[0]['cookie']['year_auto']}}','{{$search_cars[0]['cookie']['brand_auto']}}','{{$search_cars[0]['cookie']['model_auto']}}','{{$search_cars[0]['cookie']['modification_auto']}}','{{csrf_token()}}','{{$search_cars[0]['data'][0]->name}}','{{$search_cars[0]['data'][0]->displayvalue}}','{{route('modification_info')}}')
        });
    @endif
</script>
