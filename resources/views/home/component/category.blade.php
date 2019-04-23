@isset($home_category)
    <section class="light-gry-bg padding-top-60 padding-bottom-30 hidden-xs">
        <div class="container">

            <!-- heading -->
            <div class="heading">
                <h2>Каталог автотоваров</h2>
                <hr>
            </div>

            @foreach($home_category as $item)
                @php $category_tree = explode(',',$item->categories_id) @endphp
                <div class="row">
                    <div class="col-sm-3">
                        <div class="text-right home-category" style="background: {{isset($item->background)?$item->background:'#fff'}};">
                            <h5>{{$item->name}}</h5>
                            @isset($item->key_words)
                                <p>
                                    {{$item->key_words}}
                                    <br>
                                    <a class="margin-top-10 block" href="{{route('rubric',$item->hurl)}}">смотреть все</a>
                                </p>
                            @endisset
                            @isset($item->img)
                                <img src="{{asset('images/catalog/' . $item->img)}}" alt="{{$item->name}}">
                            @endisset
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div id="category-slide" class="with-nav">
                            @isset($category_tree)
                                @php $count = 0; @endphp
                                @foreach($category_tree as $key => $val)
                                    @if($count === 0)
                                        <div class="blog-post">
                                    @endif

                                        @php $data = \App\AllCategoryTree::find((int)$val) @endphp
                                        @isset($data)
                                           <div class="col-sm-4">
                                               <div class="product">
                                                   <article>
                                                       <img class="img-responsive category-img" src="{{asset('images/catalog/' . $data->image)}}" alt="{{$item->name}}" >
                                                       <a href="{{route('rubric',$data->hurl)}}" class="tittle category-title">{{$data->name}}</a>
                                                   </article>
                                               </div>
                                           </div>
                                        @endisset

                                    @if($count === 5 || $key + 1 === count($category_tree))
                                        </div>
                                        @php $count = 0; @endphp
                                    @endif
                                    @php $count++; @endphp
                                @endforeach
                            @endisset
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endisset
