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
                <h3 class="title-5 m-b-35 m-t-15">{{__('Слайды банера')}}</h3>
                <div class="table-data__tool">
                    <div class="table-data__tool-left">
                    </div>
                    <div class="table-data__tool-right">
                        <button onclick="location.href = '{{route('admin.banner.create')}}'" class="au-btn au-btn-icon au-btn--green au-btn--small">
                            <i class="zmdi zmdi-plus"></i>{{__('Создать')}}</button>
                    </div>
                </div>
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-data2">
                        <thead>
                        <tr>
                            <th>{{__('Картинка')}}</th>
                            <th>{{__('Ссылка')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($slides)
                            @forelse($slides as $slide)
                                <tr class="tr-shadow">
                                    <td><img style="width: 70px;" src="{{asset('images/banner_img/' . $slide->img)}}" alt=""></td>
                                    <td>
                                        <span class="block-email">{{$slide->link}}</span>
                                    </td>
                                    <td>
                                        <div class="table-data-feature">
                                            <button onclick="location.href = '{{route('admin.banner.edit',$slide->id)}}'" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Редактирвать')}}">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <form onsubmit="if(confirm('DELETE?')){return true}else{return false}"
                                                  action="{{route('admin.banner.destroy',$slide->id)}}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Удалить')}}">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="spacer"></tr>
                            @empty
                                <tr class="tr-shadow">
                                    <td colspan="4">
                                        <div class="alert alert-warning" role="alert">
                                            {{__('Слайдов для банера ещё нету')}}
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
                {{$slides->links()}}
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection