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
                <h3 class="title-5 m-b-35 m-t-15">{{__('Каталог товаров')}}</h3>
                <div class="table-data__tool">
                    <div class="table-data__tool-left">
                        <div class="input-group">
                            <input type="text" id="str_search" name="str_search" placeholder="Строка поиска" class="form-control" required>
                            <div class="input-group-btn">
                                <div class="rs-select2--dark rs-select2--sm rs-select2--border">
                                    <select onchange="setFilterProd(this)" class="js-select2 au-select-dark" name="field">
                                        <option selected="selected">Поля</option>
                                        <option value="articles">{{__('Артикль')}}</option>
                                        <option value="name">{{__('Название')}}</option>
                                        <option value="brand">{{__('Бренд')}}</option>
                                    </select>
                                    <div class="dropDownSelect2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-data__tool-right">
                        <button onclick="location.href = '{{route('admin.product.create')}}'" class="au-btn au-btn-icon au-btn--green au-btn--small">
                            <i class="zmdi zmdi-plus"></i>{{__('Создать')}}</button>

                        <button onclick="importPrice()" type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticModal">
                            {{__('Запустить импорт')}}
                        </button>
                        <script>
                            function importPrice() {
                                $('#load-win').html(`<i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`);
                                $.post("{{route('admin.start_import')}}",{ _token: "{{csrf_token()}}"},function () {
                                    $('#load-win').html('<p>Загрузка прошла успешно. Детальную информацию можно просмотреть в истории импортов</p>');
                                });
                            }
                        </script>
                    </div>
                </div>
                @if(session()->has("admin_filter.fields") && !empty(session("admin_filter.fields")))
                    <div class="row">
                        <div class="col-12 p-t-20 p-b-10">
                            <div class="col-12 text-right">
                                <button onclick="$.get(`{{route('admin.product.filter')}}?clear_admin_filter=true`,()=>{location.reload()})" type="button" class="btn btn-link btn-sm">
                                    <i class="fa fa-trash" aria-hidden="true"></i>&nbsp; {{__('Отменить')}}</button>
                            </div>
                            <div class="alert alert-info" role="alert">
                                <h4 class="alert-heading">{{__('Поиск по таким полям')}}</h4>
                                @foreach(session("admin_filter.fields") as $data)
                                    <p><strong>{{$data[0]}}</strong> - "{{$data[1]}}"</p>
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                <div class="table-responsive table--no-card m-b-30">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                        <tr>
                            <th>Артикул</th>
                            <th>Наименование</th>
                            <th>Бренд</th>
                            <th class="text-right">Цена</th>
                            <th class="text-right"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($products)
                            @forelse($products as $product)
                                <tr>
                                    <td>{{$product->articles}}</td>
                                    <td>{{$product->name}}</td>
                                    <td>{{$product->brand}}</td>
                                    <td class="text-right">{{$product->price}}</td>
                                    <td class="text-right">
                                        <div class="table-data-feature">
                                            <button onclick="location.href = '{{route('admin.product.edit',$product->id)}}'" class="item" data-toggle="tooltip" data-placement="top" title="Отредактировать">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <form onsubmit="if(confirm('DELETE?')){return true}else{return false}"
                                                  action="{{route('admin.product.destroy',$product->id)}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Удалить')}}">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="tr-shadow">
                                    <td colspan="5">
                                        <div class="alert alert-warning" role="alert">
                                            {{__('Товаров ещё нету')}}
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
                {{$products->links()}}
            </div>
        </div>
        @component('admin.component.footer')@endcomponent

        <!-- modal static -->
            <div class="modal fade" id="staticModal" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true"
                 data-backdrop="static">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticModalLabel">Импорт прайс-листов</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center" id="load-win"></div>
                    </div>
                </div>
            </div>
            <!-- end modal static -->
    </div>

    <script>
        function setFilterProd(obj) {
            const str_search = $('#str_search').val();
            $.get(`{{route('admin.product.filter')}}?str_search=${str_search}&field=${$(obj).val()}`,()=>{location.reload()});
        }
    </script>

@endsection