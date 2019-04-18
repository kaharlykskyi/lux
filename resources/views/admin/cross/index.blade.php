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
            <div class="col-sm-12">
                <!-- USER DATA-->
                <div class="user-data m-b-30">
                    <h3 class="title-3 m-b-30">
                        <i class="fa fa-cogs" aria-hidden="true"></i>{{__('Кросс-номера')}}</h3>
                    <div class="filters m-b-45">
                        <div class="card">
                            <div class="card-body card-block">
                                <form action="{{route('admin.cross.index')}}" method="get" id="filter_form">
                                    <div class="row form-group">
                                        <div class="col col-sm-4">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="brand" class=" form-control-label">Марка</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <select name="brand" id="brand" class="form-control">
                                                        <option></option>
                                                        @isset($brands)
                                                            @foreach($brands as $brand)
                                                                <option @if((int)request()->query('brand') === $brand->id) selected @endif value="{{$brand->id}}">{{$brand->matchcode}}</option>
                                                            @endforeach
                                                        @endisset
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col col-sm-4">
                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="OENbr" class=" form-control-label">Номер</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <input type="text" id="OENbr" value="{{request()->query('OENbr')}}" name="OENbr" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <button onclick="$('#filter_form').submit();" class="btn btn-primary btn-sm">
                                    <i class="fa fa-dot-circle-o"></i> Фильтровать
                                </button>
                                <button onclick="location.href = '{{route('admin.cross.index')}}'" class="btn btn-danger btn-sm">
                                    <i class="fa fa-ban"></i> Отменить
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END USER DATA-->
            </div>
            <div class="col-12">
                <div class="table-data__tool">
                    <div class="table-data__tool-left">
                    </div>
                    <div class="table-data__tool-right">
                        <button onclick="location.href = '{{route('admin.cross.create')}}'" type="button" class="btn btn-info">
                            {{__('Добавить кросс')}}
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="table-responsive table--no-card m-b-30">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                        <tr>
                            <th>{{__('Марка')}}</th>
                            <th>{{__('Кросс код')}}</th>
                            <th>{{__('Производитель')}}</th>
                            <th>{{__('Артикль')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($cross)
                            @forelse($cross as $item)
                                <tr>
                                    <td>
                                        @isset($brands)
                                            @foreach($brands as $brand)
                                                @if((int)$brand->id === $item->manufacturerId)
                                                    {{$brand->matchcode}}
                                                @endif
                                            @endforeach
                                        @endisset
                                    </td>
                                    <td>
                                        {{$item->OENbr}}
                                    </td>
                                    <td>
                                        {{$item->matchcode}}
                                    </td>
                                    <td>
                                        {{$item->PartsDataSupplierArticleNumber}}
                                    </td>
                                    <td class="font-size-12-440">
                                        <div class="table-data-feature">
                                            <button onclick="location.href = '{{route('admin.cross.edit',['manufacturerId' => $item->manufacturerId,'OENbr' => $item->OENbr,'PartsDataSupplierArticleNumber' => $item->PartsDataSupplierArticleNumber,'SupplierId' => $item->SupplierId])}}'" class="item" data-toggle="tooltip" data-placement="top" title="Отредактировать">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <form onsubmit="if(confirm('DELETE?')){return true}else{return false}"
                                                  action="{{route('admin.cross.delete')}}" method="get">
                                                <input type="hidden" name="manufacturerId" value="{{$item->manufacturerId}}">
                                                <input type="hidden" name="OENbr" value="{{$item->OENbr}}">
                                                <input type="hidden" name="PartsDataSupplierArticleNumber" value="{{$item->PartsDataSupplierArticleNumber}}">
                                                <input type="hidden" name="SupplierId" value="{{$item->SupplierId}}">
                                                <button type="submit" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Удалить')}}">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="alert alert-warning" role="alert">
                                            @if (empty(request()->getQueryString()))
                                                {{__('Для отображения кроссов задайте параметры поиска')}}
                                            @else
                                                {{__('Не найдено данных')}}
                                            @endif
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
                {{$cross->links()}}
            </div>
        </div>

        @component('admin.component.footer')@endcomponent
    </div>

@endsection
