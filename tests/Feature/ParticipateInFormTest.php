<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Thread;
use App\Reply;

class ParticipateInFormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */

    public function unauthenticated_users_can_not_add_replies()
    {
        $this->withoutExceptionHandling();
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = factory(Thread::class)->create();
        $this
            ->post($thread->path() . '/replies', []);

    }

    /**
     * @test
     */

    public function an_authenticated_user_can_participate_in_form_threads()
    {
        $user = factory(User::class)->create();

        $thread = factory(Thread::class)->create();

        $reply = factory(Reply::class)->make();

        $this
            ->actingAs($user)
            ->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(302);

        $this->get($thread->path())
            ->assertSee($reply->body);

    }

    /**
     * @test
     */
    public function a_reply_requires_a_body()
    {
        $thread = create(Thread::class);
        $reply = make(Reply::class, ['body' => null]);

        $this
            ->signIn()
            ->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');

    }

}
