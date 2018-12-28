@extends('admin.layouts.admin')

@section('content')

    <div class="main-content">
        <div class="section__content section__content--p30">
            @if (session('status'))
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-info" role="alert">
                                {{ session('status') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive table--no-card m-b-30">
                            <table class="table table-borderless table-striped table-earning">
                                <thead>
                                <tr>
                                    <th>Дата</th>
                                    <th>ID заказа</th>
                                    <th>клиент</th>
                                    <th class="text-right">Общяя цена</th>
                                    <th class="text-right">Статус заказа</th>
                                </tr>
                                </thead>
                                <tbody>
                                @isset($paginatedItems)
                                    @forelse($paginatedItems as $item)
                                        <tr>
                                            <td>{{$item->updated_at}}</td>
                                            <td>{{$item->id}}</td>
                                            <td>{{$item->name}}</td>
                                            <td class="text-right">&#8372; {{$item->total_price}}</td>
                                            <td style="padding: 12px 0;">
                                                <div style="width: 90%;" class="rs-select2--dark rs-select2--md m-r-10 rs-select2--border">
                                                    <select class="js-select2" name="order_status_code">
                                                        @isset($order_code)
                                                            @foreach($order_code as $v)
                                                                <option @if($v->id === $item->oder_status) selected @endif value="{{$v->id}}">{{$v->name}}</option>
                                                            @endforeach
                                                        @endisset
                                                    </select>
                                                    <div class="dropDownSelect2"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="alert alert-warning" role="alert">
                                                    <p></p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                @endisset

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        {{$paginatedItems->links()}}
                    </div>
                </div>
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection