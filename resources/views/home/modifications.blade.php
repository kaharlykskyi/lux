@extends('layouts.app')

@section('content')

    <!-- Content -->
    <div id="content">
        <!-- Linking -->
        @component('component.breadcrumb',[
            'links' => [
                (object)['link' => route('all_brands') . '?brand=' . $brand[0]->id,'title' => 'Запчасти для ' . $brand[0]->name],
                (object)['title' => 'Модель ' . $model[0]->name]
            ]
        ])
        @endcomponent

        <section class="contact-sec padding-top-40 padding-bottom-80">
            <div class="container">
                <div class="row">
                    @isset($modification)
                        <div class="col-xs-12">
                            <ul class="list-group">
                                @foreach($modification as $item)
                                    @if($item->attributegroup === 'General' && $item->attributetype === 'ConstructionInterval')
                                        <li class="list-group-item margin-bottom-15 row">
                                            <a href="{{route('all_brands',['modification_auto' => $item->id])}}">
                                                <h6 class="text-uppercase">
                                                    {{$item->name}}<br>
                                                    <span class="small text-right">{{$item->displayvalue}}</span>
                                                </h6>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endisset
                </div>
            </div>
        </section>

    </div>
    <!-- End Content -->

@endsection
