<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */

    public function a_reply_has_an_owner()
    {

        $reply = factory('App\Reply')->create();

        $this->assertInstanceOf('App\User', $reply->owner);

    }

    /**
     * @test
     */
    public function it_knows_if_it_was_just_published()
    {
        $reply = create('App\Reply');

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMinutes(2);

        $this->assertFalse($reply->wasJustPublished());
    }
}
