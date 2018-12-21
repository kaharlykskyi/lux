<table class="table">
    <thead>
    <tr>
        <th scope="col">{{__('Название склада')}}</th>
        <th scope="col">{{__('Компания')}}</th>
        <th scope="col">{{__('Остатки')}}</th>
    </tr>
    </thead>
    <tbody>
        @forelse($stock_count as $item)
            <tr>
                <th scope="row">{{$item->name}}</th>
                <td>{{$item->company}}</td>
                <td>
                    <form action="{{route('admin.product.stock_count')}}" method="post" class="form-horizontal ajax">
                        @csrf
                        <input type="hidden" name="product_id" value="{{$product->id}}">
                        <input type="hidden" name="stock_id" value="{{$item->id}}">
                        <div class="row">
                            <div class="col-8">
                                <input type="number" name="count" value="{{$item->count}}"  class="form-control">
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
                                </button>
                            </div>
                        </div>

                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <th scope="row" colspan="3">
                    <div class="alert alert-warning" role="alert">
                        {{__('Нету информации')}}
                    </div>
                </th>
            </tr>
        @endforelse
    </tbody>
</table>
<script>
    $('.ajax').submit(function (e) {
        e.preventDefault();
        $.post($(this).attr('action'),$(this).serialize(),function (data) {
            if(data.error !== undefined){
                console.log(data.error);
                alert('Ошибка, проверте все поля');
                return false;
            }
            alert(data.response);
        });
    });
</script>