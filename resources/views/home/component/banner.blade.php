<!-- Slid Sec -->
@if(isset($slides[0]))
<section class="slid-sec">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 no-padding">
                <div class="tp-banner-container">
                    <div class="tp-banner-full">
                        <ul>
                            @foreach($slides as $slide)
                                <li data-transition="random" data-slotamount="7" data-masterspeed="300"  data-saveperformance="off" >
                                    <!-- MAIN IMAGE -->
                                    <img src="{{asset('images/banner_img/' . $slide->img)}}"  alt="slider"  data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat">
                                    @if(isset($slide->text) && !empty($slide->text))
                                        <div class="tp-caption sfr tp-resizeme"
                                             data-x="left" data-hoffset="50"
                                             data-y="center" data-voffset="-110"
                                             data-speed="800"
                                             data-start="1300"
                                             data-easing="Power3.easeInOut"
                                             data-splitout="none"
                                             data-elementdelay="0.03"
                                             data-endelementdelay="0.4"
                                             data-endspeed="100"
                                             style="z-index: 5;"><div>{!! $slide->text !!}</div></div>
                                    @endif
                                    @isset($slide->link)
                                        <div class="tp-caption lfb tp-resizeme scroll"
                                             data-x="left" data-hoffset="50"
                                             data-y="center" data-voffset="80"
                                             data-speed="800"
                                             data-start="1300"
                                             data-easing="Power3.easeInOut"
                                             data-elementdelay="0.1"
                                             data-endelementdelay="0.1"
                                             data-endspeed="100"
                                             data-scrolloffset="0"
                                             style="z-index: 8;">
                                            <a href="{{$slide->link}}" class="btn-round big">{{isset($slide->str_link)?$slide->str_link:'link'}}</a>
                                        </div>
                                    @endisset
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
