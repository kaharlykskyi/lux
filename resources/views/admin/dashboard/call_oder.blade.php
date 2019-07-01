@extends('admin.layouts.admin')

@section('content')

    <div class="container-fluid m-t-75">
        @if (session('status'))
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-info" role="alert">
                        {{ session('status') }}
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <!-- DATA TABLE -->
                <h3 class="title-5 m-b-35 m-t-15">{{__('Заказ звонка')}}</h3>
                <div class="col-12 m-b-15">
                    <a href="{{route('admin.dashboard')}}" class="btn btn-success">{{__('Назад')}}</a>
                </div>
                <div class="table-responsive table--no-card m-b-30">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                        <tr>
                            <th>Имя</th>
                            <th>Телефон</th>
                            <th>Дата</th>
                            <th class="text-right">Обработан</th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($call_orders)
                            @forelse($call_orders as $item)
                                <tr>
                                    <td>{{$item->fio}}</td>
                                    <td>{{$item->phone}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td class="text-right">
                                        <input onchange="callOrderStatus('{{$item->id}}','{{$item->status}}');" type="checkbox" @if($item->status === 1) checked @endif value="{{$item->status}}">
                                    </td>
                                </tr>
                            @empty
                                <tr class="tr-shadow">
                                    <td colspan="4">
                                        <div class="alert alert-warning" role="alert">
                                            {{__('Заказов на звонок ещё нету')}}
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        @endisset

                        </tbody>
                    </table>
                </div>
                <!-- END DATA TABLE -->
            </div>
            <div class="col-sm-12">
                {{$call_orders->links()}}
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>
<script>
    function callOrderStatus(id,status) {
        $.post(`{{route('admin.call_orders')}}`,{'id':id,'_token':'{{csrf_token()}}','status':status},function (data) {
            alert(data);
        });
    }
</script>
@endsection
