<footer>
    <div class="container">

        <!-- Footer Upside Links -->
        <div class="foot-link">
        </div>
        <div class="row">

            <!-- Contact -->
            <div class="col-md-4">
                <h4>Contact SmartTech!</h4>
                <p>Address: 45 Grand Central Terminal New York, NY 1017
                    United State USA</p>
                <p>Phone: (+100) 123 456 7890</p>
                <p>Email: Support@smarttech.com</p>
                <div class="social-links"> <a href="#."><i class="fa fa-facebook"></i></a> <a href="#."><i class="fa fa-twitter"></i></a> <a href="#."><i class="fa fa-linkedin"></i></a> <a href="#."><i class="fa fa-pinterest"></i></a> <a href="#."><i class="fa fa-instagram"></i></a> <a href="#."><i class="fa fa-google"></i></a> </div>
            </div>

            <!-- Categories -->
            <div class="col-md-3">
                <h4>Categories</h4>
                <ul class="links-footer">
                    @isset($pages)
                        @foreach($pages as $page)
                            @if($page->footer_column === 1)
                                <li><a href="{{route('page',$page->alias)}}">{{$page->title}}</a></li>
                            @endif
                        @endforeach
                    @endisset
                </ul>
            </div>

            <!-- Categories -->
            <div class="col-md-3">
                <h4>Customer Services</h4>
                <ul class="links-footer">
                    @isset($pages)
                        @foreach($pages as $page)
                            @if($page->footer_column === 2)
                                <li><a href="{{route('page',$page->alias)}}">{{$page->title}}</a></li>
                            @endif
                        @endforeach
                    @endisset
                </ul>
            </div>

            <!-- Categories -->
            <div class="col-md-2">
                <h4>Information</h4>
                <ul class="links-footer">
                    @isset($pages)
                        @foreach($pages as $page)
                            @if($page->footer_column === 3)
                                <li><a href="{{route('page',$page->alias)}}">{{$page->title}}</a></li>
                            @endif
                        @endforeach
                    @endisset
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- Rights -->
<div class="rights">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <p>Copyright © {{date('Y')}} <a href="#." class="ri-li"> SmartTech </a>HTML5 template. All rights reserved</p>
            </div>
            <div class="col-sm-6 text-right"> <img src="{{asset('images/card-icon.png')}}" alt=""> </div>
        </div>
    </div>
</div>