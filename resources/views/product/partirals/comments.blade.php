@guest
    <div class="alert alert-warning" role="alert">
        {{__('Отзывы могут оставлять только зарегистрированные пользователи')}}
    </div>
@else
    <form action="{{route('product.comment')}}" method="post" id="product-comment-form">
        <input type="hidden" name="product_id" value="{{$product->id}}">
        @csrf
        <div class="form-group">
            <label for="comment-text">{{__('Напишите отзыв и поставте отценку')}}</label>
            <textarea class="form-control" style="height: 200px;" id="comment-text" name="text" required></textarea>
        </div>
        <div class="form-group" style="height: 33px;">
            <div id="reviewStars-input">
                <input id="star-4" value="5" checked type="radio" name="rating"/>
                <label title="gorgeous" for="star-4"></label>

                <input id="star-3" value="4" type="radio" name="rating"/>
                <label title="good" for="star-3"></label>

                <input id="star-2" value="3" type="radio" name="rating"/>
                <label title="regular" for="star-2"></label>

                <input id="star-1" value="2" type="radio" name="rating"/>
                <label title="poor" for="star-1"></label>

                <input id="star-0" value="1" type="radio" name="rating"/>
                <label title="bad" for="star-0"></label>
            </div>
        </div>
        <div class="form-group margin-top-10">
            <button style="background: #0a95da" type="submit" class="btn btn-default">{{__('Отправить')}}</button>
        </div>
    </form>
    <!-- Comments -->
    <div class="comments">
        <!-- Comments -->
        <ul id="comment_list">
            @forelse($product->comment as $comment)
                @php $user = \App\User::find($comment->user_id); @endphp
                <li class="media">
                    <div class="media-body">
                        <h6>{{$user->name}} <span><i class="fa fa-bookmark-o"></i> {{date_format($comment->created_at,'Y-m-d')}} </span> </h6>
                        <p>{{$comment->text}}</p>
                        <p class="rev">
                            <i class="fa {{$comment->rating > 0?'fa-star':'fa-star-o'}}"></i>
                            <i class="fa {{$comment->rating > 1?'fa-star':'fa-star-o'}}"></i>
                            <i class="fa {{$comment->rating > 2?'fa-star':'fa-star-o'}}"></i>
                            <i class="fa {{$comment->rating > 3?'fa-star':'fa-star-o'}}"></i>
                            <i class="fa {{$comment->rating > 4?'fa-star':'fa-star-o'}}"></i>
                        </p>
                    </div>
                </li>
            @empty
                <div class="alert alert-info" role="alert">{{__('Отзывов ещё нету')}}</div>
            @endforelse
        </ul>
    </div>
@endguest
