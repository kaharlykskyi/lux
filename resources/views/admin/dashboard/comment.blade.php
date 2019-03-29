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
                <h3 class="title-5 m-b-35 m-t-15">{{__('Коментарии')}}</h3>
                <div class="col-12 m-b-15">
                    <a href="{{route('admin.dashboard')}}" class="btn btn-success">{{__('Назад')}}</a>
                </div>
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-data2">
                        <thead>
                        <tr>
                            <th>{{__('Автор')}}</th>
                            <th>{{__('Дата')}}</th>
                            <th>{{__('Текст')}}</th>
                            <th>{{__('Товар')}}</th>
                            <th>{{__('Рейтинг')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($comments)
                            @forelse($comments as $comment)
                                <tr class="tr-shadow">
                                    <td><span class="block-email">{{$comment->user->name}}</span></td>
                                    <td>{{$comment->created_at}}</td>
                                    <td>
                                        {{$comment->text}}
                                    </td>
                                    <td>{{$comment->product->articles}}</td>
                                    <td>{{$comment->rating}}</td>
                                    <td>
                                        <div class="table-data-feature">
                                            <form onsubmit="if(confirm('DELETE?')){return true}else{return false}"
                                                  action="{{route('admin.comment')}}" method="post">
                                                @csrf
                                                <input type="hidden" value="{{$comment->id}}" name="comment_id">
                                                <button type="submit" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Удалить')}}">
                                                    <i class="zmdi zmdi-delete"></i>
                                                </button>
                                            </form>
                                            <button onclick="location.href = '{{route('product',str_replace('/','@',($comment->product->articles)))}}'" class="item m-l-5" data-toggle="tooltip" data-placement="top" title="{{__('Просмотреть')}}">
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
                {{$comments->links()}}
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

@endsection
