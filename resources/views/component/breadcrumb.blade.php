<div class="linking">
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="{{route('home')}}">Главная</a></li>
            @isset($links)
                @foreach($links as $link)
                    @php
                        $end = end($links);
                    @endphp
                    @if($link->title === $end->title)
                        <li class="active">{{$link->title}}</li>
                    @else
                        <li><a href="{{$link->link}}">{{$link->title}}</a></li>
                    @endif
                @endforeach
            @endisset
        </ol>
    </div>
</div>