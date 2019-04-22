@extends('admin.layouts.admin')

@section('content')

    <div class="main-content p-t-85">
        <div class="section__content">
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
                        <h3 class="title-5 m-b-35 m-t-15">{{__('Главные категории для домашней страници')}}</h3>
                    </div>
                    <div class="col-12 m-b-15 m-t-15 text-right">
                        <a href="{{route('admin.home_category.create')}}" class="btn btn-primary">{{__('Создать')}}</a>
                    </div>
                    <div class="col-12">
                        <div class="table--no-card m-b-30">
                            <table class="table table-borderless table-striped table-earning">
                                <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>hurl</th>
                                    <th>Картинка</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @isset($home_category)
                                        @forelse($home_category as $item)
                                            <tr>
                                                <td>
                                                    {{$item->name}}
                                                </td>
                                                <td>
                                                    {{$item->hurl}}
                                                </td>
                                                <td>
                                                    <img style="width: 40px;" src="{{asset('images/catalog/' . $item->img)}}" alt="">
                                                </td>
                                                <td>
                                                    <div class="table-data-feature">
                                                        <button onclick="location.href = '{{route('admin.home_category.edit',['home_category' => $item->id])}}'" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Редактирвать')}}">
                                                            <i class="zmdi zmdi-edit"></i>
                                                        </button>
                                                        <form onsubmit="if(confirm('DELETE?')){return true}else{return false}"
                                                              action="{{route('admin.home_category.destroy',$item->id)}}" method="post">
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
                                            <tr>
                                                <td colspan="5">
                                                    <div class="alert alert-warning" role="alert">
                                                        <p>Категорий ещё нету</p>
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
                        {{$home_category->links()}}
                    </div>
                </div>
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>
@endsection
