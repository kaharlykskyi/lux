@extends('admin.layouts.admin')

@section('content')

    <div class="container-fluid m-t-75">
        <div class="row">
            <div class="col-12">
                <h3 class="title-5 m-b-35 m-t-15">{{__('Сопоставление брендов')}}</h3>
            </div>
            <div class="col-md-12">
                <div class="table-responsive table--no-card m-b-30">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                        <tr>
                            <th>Название/Бренд</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($alias)
                            @forelse($alias as $item)
                                <tr>
                                    <td>
                                        <form action="" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$item->id}}">
                                            <input style="border-bottom: 1px solid" type="text" name="name" value="{{$item->name}}">
                                            <select name="tecdoc_name">
                                                @foreach($suppliers as $supplier)
                                                    <option @if(strtolower($supplier->description) == strtolower($item->tecdoc_name)) selected="selected" @endif value="{{$supplier->description}}">
                                                        {{$supplier->description}}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" style="padding: 0 5px;font-size: 13px;" class="btn btn-primary small">
                                                сохранить
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <button onclick="location.href='{{route('admin.no_brands.delete',$item->id)}}'" style="padding: 0 5px;font-size: 13px;" class="btn btn-danger small">
                                            удалить
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr class="tr-shadow">
                                    <td colspan="5">
                                        <div class="alert alert-warning" role="alert">
                                            {{__('Сопоставлений ещё нету')}}
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
                {{$alias->links()}}
            </div>
        </div>
        @component('admin.component.footer')@endcomponent

    </div>

@endsection
