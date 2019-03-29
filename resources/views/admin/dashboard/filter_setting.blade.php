@extends('admin.layouts.admin')

@section('content')

    <div class="main-content">
        <div class="section__content section__content--p30">
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
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link @if(URL::current() === route('admin.filter','use')) active @endif" href="{{route('admin.filter','use')}}">{{__('Используються')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if(URL::current() === route('admin.filter','all')) active @endif" href="{{route('admin.filter','all')}}">{{__('Все отрибуты')}}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12">
                        @if($status === 'use')
                            <div class="card">
                                <div class="card-body card-block">
                                    <form action="{{route('admin.filter','use')}}" method="post" class="form-horizontal">
                                        @csrf

                                        <div class="table-responsive table--no-card m-b-30">
                                            <table class="table table-borderless table-striped table-earning">
                                                <thead>
                                                <tr>
                                                    <th>Использовать</th>
                                                    <th>Название</th>
                                                    <th>Псевдоним(для URL)</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @isset($use_filters)
                                                    @forelse($use_filters as $item)
                                                        <tr>
                                                            <td><input type="checkbox" @if($item->use === 1) checked @endif  name="use_{{$item->id}}"></td>
                                                            <td>{{$item->description}}</td>
                                                            <td>
                                                                <input onblur="" type="text" name="hurl_{{$item->id}}" value="{{$item->hurl}}">
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3">
                                                                <div class="alert alert-warning" role="alert">
                                                                    <p>Не выбрано данных для фильтра</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                @endisset

                                                </tbody>
                                            </table>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-dot-circle-o"></i> {{__('Сохранить')}}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-12">
                        @if($status === 'all')
                            <div class="table-responsive table--no-card m-b-30">
                                <table class="table table-borderless table-striped table-earning">
                                    <thead>
                                    <tr>
                                        <th>Название</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @isset($all_filter_settings)
                                        @forelse($all_filter_settings as $item)
                                            <tr>
                                                <td>
                                                    {{$item->description}}
                                                    @isset($use_filters)
                                                        @php $save_setting = true; @endphp
                                                        @foreach($use_filters as $v)
                                                            @if($v->filter_id === $item->id)
                                                                @php $save_setting = false; @endphp
                                                            @endif
                                                        @endforeach
                                                        @if($save_setting)
                                                            <button id="settings_{{$item->id}}" onclick="addSettings('{{$item->id}}','{{$item->description}}')" type="button" class="btn btn-outline-primary m-l-15">
                                                                <i class="fa fa-star"></i>&nbsp; Добавить</button>
                                                        @endif
                                                    @endisset
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">
                                                    <div class="alert alert-warning" role="alert">
                                                        <p>Данных не найдено</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    @endisset

                                    </tbody>
                                </table>
                            </div>
                            {{$all_filter_settings->links()}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @component('admin.component.footer')@endcomponent
    </div>
    <script>
        function addSettings(id,desc) {
            $.post(`{{route('admin.filter','all')}}`,{'id':id,'desc':desc,'_token':'{{csrf_token()}}'},function () {
                $(`#settings_${id}`).remove();
            });
        }
    </script>
@endsection
