<?php

namespace Tests\Unit;

use App\Reply;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = factory(Thread::class)->create();

    }

    /**
     * @test
     */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /**
     * @test
     */

    public function a_thread_can_make_a_string_path()
    {

        $this
            ->assertEquals('/threads/' . $this->thread->channel->slug . '/' . $this->thread->id, $this->thread->path());
    }

    /**
     * @test
     */

    public function a_thread_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);
    }

    /**
     * @test
     */

    public function a_thread_can_add_new_reply()
    {
        $reply = factory('App\Reply')->make(['thread_id' => $this->thread->id]);
        $this->thread->addReply($reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
    }

    /**
     * @test
     */

    public function a_thread_belongs_to_a_channel()
    {
        $this->assertInstanceOf('App\Channel', $this->thread->channel);
    }
}
