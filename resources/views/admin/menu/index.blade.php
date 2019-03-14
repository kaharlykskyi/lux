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
                <h3 class="title-5 m-b-35 m-t-15">{{__('Выбор категорий что будут отображаться в меню хедера')}}</h3>
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-data2">
                        <thead>
                        <tr>
                            <th>{{__('Заголовок')}}</th>
                            <th>{{__('Показывать в меню')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($tecdoc_category)
                            @forelse($tecdoc_category as $category)
                                @if(!empty($category->assemblygroupdescription))
                                    <tr class="tr-shadow">
                                        @php $save_category = \App\TopMenu::where('tecdoc_title',$category->assemblygroupdescription)->first(); @endphp
                                        <td>{{isset($save_category)?$save_category->title:$category->assemblygroupdescription}}</td>
                                        <td>
                                            <span class="block-email">@if(isset($save_category) && $save_category->show_menu === 1){{__('да')}}@else{{__('нет')}}@endif</span>
                                        </td>
                                        <td>
                                            <div class="table-data-feature">
                                                <button onclick="location.href = '{{route('admin.menu.edit',['id' => urldecode($category->assemblygroupdescription)])}}'" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Редактирвать')}}">
                                                    <i class="zmdi zmdi-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                @endif
                            @empty
                                <tr class="tr-shadow">
                                    <td colspan="4">
                                        <div class="alert alert-warning" role="alert">
                                            {{__('Данных нету')}}
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
                {{$tecdoc_category->links()}}
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection
