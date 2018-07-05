<?php

namespace Tests\Feature;


use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscribeToThreadsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function unauthenticated_user_may_not_subscribe_a_thread()
    {
        $thread = create('App\Thread');

        $this->post($thread->path() . '/subscriptions')
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_subscribe_to_thread()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        $thread = create('App\Thread');
        //
        $this->post($thread->path() . '/subscriptions')
            ->assertStatus(302);

        $this->assertCount(1, $thread->subscriptions);


//        $this->assertCount(0, auth()->user()->notifications);
//
//        // every time a new reply is left
//        $thread->addReply([
//            'user_id'   => auth()->id(),
//            'body'      => 'anything'
//        ]);
//
//        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /**
     * @test
     */
    public function an_authenticated_user_can_unsubscribe_to_thread()
    {
        $this->signIn();
        $thread = create('App\Thread');
        $thread->subscribe();

        $this->delete($thread->path() . '/subscriptions');

        $this->assertDatabaseMissing('thread_subscriptions', ['thread_id' => $thread->id]);
    }
}
