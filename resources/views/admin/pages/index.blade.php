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
                <h3 class="title-5 m-b-35 m-t-15">{{__('Страници сайта')}}</h3>
                <div class="table-data__tool">
                    <div class="table-data__tool-left">
                    </div>
                    <div class="table-data__tool-right">
                        <button onclick="location.href = '{{route('admin.page.create')}}'" class="au-btn au-btn-icon au-btn--green au-btn--small">
                            <i class="zmdi zmdi-plus"></i>{{__('Создать')}}</button>
                    </div>
                </div>
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-data2">
                        <thead>
                        <tr>
                            <th>{{__('Заголовок')}}</th>
                            <th>{{__('Ссылка')}}</th>
                            <th>{{__('Колонка футера')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($pages)
                            @forelse($pages as $page)
                                <tr class="tr-shadow">
                                    <td>{{$page->title}}</td>
                                    <td>
                                        <span class="block-email">{{$page->alias}}</span>
                                    </td>
                                    <td>{{$page->footer_column}}</td>
                                    <td>
                                        <div class="table-data-feature">
                                            <button onclick="location.href = '{{route('admin.page.edit',$page->id)}}'" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Редактирвать')}}">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <form onsubmit="if(confirm('DELETE?')){return true}else{return false}"
                                                  action="{{route('admin.page.destroy',$page->id)}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Удалить')}}">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </form>
                                            <button onclick="location.href = '{{route('admin.page.show',$page->id)}}'" class="item m-l-5" data-toggle="tooltip" data-placement="top" title="{{__('Просмотреть')}}">
                                                <i class="zmdi zmdi-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="spacer"></tr>
                                @empty
                                <tr class="tr-shadow">
                                    <td colspan="4">
                                        <div class="alert alert-warning" role="alert">
                                            {{__('Страниц ещё нету')}}
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
                {{$pages->links()}}
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection