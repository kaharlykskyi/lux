<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->insert([
            'alias' => 'about-us',
            'title' => 'О магазине',
            'description' => 'описание',
            'content' => '<!-- About Sec -->
                            <section class="about-sec padding-top-60 padding-bottom-60">
                              <div class="container"> 
                                
                                <!-- About Adds -->
                                <div class="about-adds">
                                  <div class="position-center-center">
                                    <h2>SmartTech <small> Digital & Electronic PSD Template!</small></h2>
                                    <a href="#." class="btn-round">Shop with Us</a> </div>
                                </div>
                              </div>
                            </section>
                            
                            <!-- Shipping Info -->
                            <section class="shipping-info padding-bottom-60">
                              <div class="container">
                                <ul>
                                  <!-- Free Shipping -->
                                  <li>
                                    <div class="media-left"> <i class="flaticon-delivery-truck-1"></i> </div>
                                    <div class="media-body">
                                      <h5>Free Shipping</h5>
                                      <span>On order over $99</span></div>
                                  </li>
                                  <!-- Money Return -->
                                  <li>
                                    <div class="media-left"> <i class="flaticon-arrows"></i> </div>
                                    <div class="media-body">
                                      <h5>Money Return</h5>
                                      <span>30 Days money return</span></div>
                                  </li>
                                  <!-- Support 24/7 -->
                                  <li>
                                    <div class="media-left"> <i class="flaticon-operator"></i> </div>
                                    <div class="media-body">
                                      <h5>Support 24/7</h5>
                                      <span>Hotline: (+100) 123 456 7890</span></div>
                                  </li>
                                  <!-- Safe Payment -->
                                  <li>
                                    <div class="media-left"> <i class="flaticon-business"></i> </div>
                                    <div class="media-body">
                                      <h5>Safe Payment</h5>
                                      <span>Protect online payment</span></div>
                                  </li>
                                </ul>
                              </div>
                            </section>
                            
                            <!-- Team -->
                            <section class="padding-top-60 padding-bottom-60">
                              <div class="container"> 
                                
                                <!-- heading -->
                                <div class="heading">
                                  <h2>Meet Our Team</h2>
                                  <hr>
                                </div>
                                <div class="team">
                                  <div class="row">
                                    <div class="col-md-3"> <img class="img-responsive" src="images/team-img-1.jpg" alt="" >
                                      <h3>Tom Doe</h3>
                                      <span>Ceo/Founder SmartTech</span> </div>
                                    <div class="col-md-3"> <img class="img-responsive" src="images/team-img-2.jpg" alt="" >
                                      <h3>Tom Doe</h3>
                                      <span>Ceo/Founder SmartTech</span> </div>
                                    <div class="col-md-3"> <img class="img-responsive" src="images/team-img-3.jpg" alt="" >
                                      <h3>Tom Doe</h3>
                                      <span>Ceo/Founder SmartTech</span> </div>
                                    <div class="col-md-3"> <img class="img-responsive" src="images/team-img-4.jpg" alt="" >
                                      <h3>Tom Doe</h3>
                                      <span>Ceo/Founder SmartTech</span> </div>
                                  </div>
                                </div>
                              </div>
                            </section>
                            
                            <!-- Clients img -->
                            <section class="light-gry-bg clients-img">
                              <div class="container">
                                <ul>
                                  <li><img src="images/c-img-1.png" alt="" ></li>
                                  <li><img src="images/c-img-2.png" alt="" ></li>
                                  <li><img src="images/c-img-3.png" alt="" ></li>
                                  <li><img src="images/c-img-4.png" alt="" ></li>
                                  <li><img src="images/c-img-5.png" alt="" ></li>
                                </ul>
                              </div>
                            </section>',
            'user_id' => 1,
            'footer_column' => 3,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now(),
        ]);
    }
}
