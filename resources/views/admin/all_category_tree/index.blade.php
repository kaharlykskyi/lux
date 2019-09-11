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
            @include('admin.component.back')
            <div class="col-md-12">
                <!-- DATA TABLE -->
                <h3 class="title-5 m-b-35 m-t-15">{{__('Общие категории сайта')}}</h3>
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-data2">
                        <thead>
                        <tr>
                            <th>{{__('Картинка')}}</th>
                            <th>{{__('Название')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($categories)
                            @forelse($categories as $category)
                                @if(!empty($category->name))
                                    <tr>
                                        <th>
                                            @php
                                                $category_title = '';
                                                if(request()->has('level')){
                                                    if (!empty($category->usagedescription)){
                                                        $category_title=$category->usagedescription;
                                                    }elseif (!empty($category->normalizeddescription)){
                                                        $category_title=$category->normalizeddescription;
                                                    }elseif (!empty($category->name)){
                                                        $category_title=$category->name;
                                                    }
                                                }else{
                                                    $category_title = $category->name;
                                                }
                                            @endphp
                                            <img style="width: 35px;height: auto;" src="@if(isset($category->image)) {{asset('images/catalog/' .$category->image)}} @else {{asset('images/map-locator.png')}} @endif" alt="">
                                        </th>
                                        <th>
                                            @if (request()->has('level'))
                                                @php $name4 = !empty($category->usagedescription)?' -->' .$category->usagedescription:'' @endphp
                                                {{$category->name.' -->'.$category->normalizeddescription . $name4}}
                                            @else
                                                {{$category_title}}
                                            @endif
                                            @if((int)request()->query('level') === 1 && isset($category->custom_id))
                                                    <div class="form-group">
                                                        <label class="small" for="childRoot_{{$category->custom_id}}">Переопределение родительской категории</label>
                                                        <select onchange="changeParent({{$category->custom_id}})" data-id="{{$category->custom_id}}" class="form-control" id="childRoot_{{$category->custom_id}}">
                                                            <option value="{{$parent->id}}">{{$parent->name}}</option>
                                                            @foreach ($parent->subCategory as $subCategory)
                                                                <option @if ($subCategory->id === $category->parent_category) selected @endif value="{{$subCategory->id}}">{{$subCategory->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                            @endif
                                        </th>
                                        <th>
                                            <div class="table-data-feature">
                                                <button onclick="location.href = '{{route('admin.all_category.edit')}}?id={{$category->custom_id}}{{isset($parent->id)?"&parent_id={$parent->id}":''}}'" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Редактирвать')}}">
                                                    <i class="zmdi zmdi-edit"></i>
                                                </button>
                                                @if((int)request()->query('level') === 0 && isset($category->custom_id))
                                                    <button onclick="location.href = '{{route('admin.all_category.index')}}?parent_category={{$category->custom_id}}&level=1'" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Дочерние категории')}}">
                                                        <i class="fa fa-sitemap" style="font-size: 17px;" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </th>
                                    </tr>
                                @endif
                            @empty
                                <tr class="tr-shadow">
                                    <td colspan="4">
                                        <div class="alert alert-warning" role="alert">
                                            {{__('Данных ещё не обнаружено')}}
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
            @isset($categories)
                <div class="col-sm-12">
                    {{$categories->links()}}
                </div>
            @endisset
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection
@section('script')
   <script>
       function changeParent(id) {
           const parent_id = $('#childRoot_'+id).val()
           $.post('{{route('admin.all_category.edit')}}',{
               _token:'{{csrf_token()}}',
               change_root:true,
               parent_id,
               id
           },function (data) {
               if (data){
                   location.reload()
               } else{
                   alert('Error')
               }
           })
       }
   </script>
@stop
