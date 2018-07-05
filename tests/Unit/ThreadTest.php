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
        $this->thread->addReply([
            'thread_id' => $reply->thread_id,
            'user_id'   => $reply->user_id,
            'body'      => $reply->body,
        ]);

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
    }

    /**
     * @test
     */

    public function a_thread_belongs_to_a_channel()
    {
        $this->assertInstanceOf('App\Channel', $this->thread->channel);
    }

    /**
     * @test
     */
    public function a_thread_can_be_subscribed_to()
    {
        $thread = create('App\Thread');
        $this->actingAs(create('App\User'));
        $thread->subscribe();

        $this->assertEquals(1, $thread->subscriptions()->where('user_id', auth()->id())->count());
    }

    /**
     * @test
     */
    public function a_thread_can_be_unsubscribed_from()
    {
        $thread = create('App\Thread');
        $thread->subscribe($userId = 1);
        $this->assertEquals(1, $thread->subscriptions()->where('user_id', 1)->count());

        $thread->unsubscribe($userId);
        $this->assertEquals(0, $thread->subscriptions()->where('user_id', 1)->count());

    }

    /**
     * @test
     */
    public function a_thread_may_knows_if_it_is_subscribed_to()
    {
        $this->signIn();
        $thread = create('App\Thread');

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    /**
     * @test
     */
    public function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $thread = create('App\Thread')->subscribe();

        $this->assertTrue($thread->hasUpdatesFor());

        auth()->user()->read($thread);

        $this->assertFalse($thread->hasUpdatesFor());


    }
}
