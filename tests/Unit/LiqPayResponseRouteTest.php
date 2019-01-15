<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LiqPayResponseRouteTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRoute()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->json('POST', 'http://lux.loc:81/liqpay/response', ['name' => 'Sally']);

        $response->assertStatus(200);
        $response->assertOk();
    }
}
