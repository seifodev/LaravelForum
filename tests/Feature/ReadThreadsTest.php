<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Thread;
use App\Reply;
use App\Channel;

class ReadThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;

    public function setUp()
    {
        parent::setUp();
//        $this->thread = factory(Thread::class)->create();

    }


    /**
     * @test
     */

    public function a_user_can_browse_threads()
    {

        $thread = create(Thread::class);
        $this->get('/threads')
            ->assertStatus(200)
            ->assertSee($thread->title);
    }


    /**
     * Check if the user can browse a single thread
     * @test
     */
    public function a_user_can_browse_a_single_thread()
    {
        $thread = create(Thread::class);
        $this->get($thread->path())
            ->assertStatus(200)
            ->assertSee($thread->title);
    }

    /**
     * @test
     */
    public function a_user_can_read_replies_belongs_to_a_thread()
    {

        $thread = create(Thread::class);
        $reply = factory(Reply::class)->create(['thread_id' => $thread->id]);

        $this->get($thread->path())
            ->assertStatus(200)
            ->assertSee($reply->body);

    }

    /**
     * @test
     */
    public function a_user_can_filter_threads_according_to_a_channel()
    {

        $channel = create(Channel::class);
        $threadInCahnnel = create(Thread::class, ['channel_id' => $channel->id]);
        $threadNotInChannel = create(Thread::class);

        $this
            ->get('/threads/' . $channel->slug)
            ->assertStatus(200)
            ->assertSee($threadInCahnnel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /**
     * @test
     */
    public function a_user_can_filter_threads_by_any_username()
    {

        $user = create('App\User', ['name' => 'seifoDev']);

        $threadBySeifoDev = create('App\Thread', ['user_id' => $user->id]);
        $threadNotBySeifoDev = create('App\Thread');

        $response = $this->get('/threads/?by=seifoDev');

        $response
            ->assertStatus(200)
            ->assertSee($threadBySeifoDev->title)
            ->assertDontSee($threadNotBySeifoDev->title);

    }

    /**
     * @test
     */
    public function a_user_can_filter_threads_by_popularity()
    {
        // Popularity is according to the number of replies

        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithTwoReplies], 2);

        $threadWithThreeReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithThreeReplies], 3);

        $threadWithNoReplies = create('App\Thread');

        $response = $this->json('get', '/threads?popular=1');

        $this->assertEquals([3, 2, 0], array_column($response->json(), 'replies_count'));
    }


}
