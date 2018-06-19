<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavouritesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_favourite_may_belongs_to_a_reply()
    {
        $reply = create('App\Reply');
        $favorite = create('App\Favourite', ['favourited_id' => $reply->id, 'favourited_type' => 'App\Reply']);

        $this->assertInstanceOf('App\Reply', $favorite->favourited);
        $this->assertEquals($reply->body, $favorite->favourited->body);
    }
}
