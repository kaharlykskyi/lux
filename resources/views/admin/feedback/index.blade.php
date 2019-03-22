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
                <h3 class="title-5 m-b-35 m-t-15">{{__('Обратная связь')}}</h3>
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-data2">
                        <thead>
                        <tr>
                            <th>{{__('Имя')}}</th>
                            <th>{{__('Телефон')}}</th>
                            <th>{{__('E-mail')}}</th>
                            <th>{{__('Сообщение')}}</th>
                            <th>{{__('Дата')}}</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @isset($feedback)
                            @forelse($feedback as $item)
                                <tr class="tr-shadow">
                                    <td>{{$item->name}}</td>
                                    <td>
                                        <span class="block-email">{{$item->email}}</span>
                                    </td>
                                    <td>{{$item->phone}}</td>
                                    <td>{{$item->message}}</td>
                                    <td>{{$item->created_at}}</td>
                                    <td>
                                        <div class="table-data-feature">
                                            <button onclick="showFeedBackModal('{{$item->email}}')" class="item" data-toggle="tooltip" data-placement="top" title="{{__('Ответить')}}">
                                                <i class="zmdi zmdi-edit"></i>
                                            </button>
                                            <form onsubmit="if(confirm('DELETE?')){return true}else{return false}"
                                                  action="{{route('admin.feedback.delete',$item->id)}}" method="post">
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
                                    <td colspan="6">
                                        <div class="alert alert-warning" role="alert">
                                            {{__('Ничего не найдено')}}
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
                {{$feedback->links()}}
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>

    @include('admin.feedback.partrials.send_feedback')

@endsection
