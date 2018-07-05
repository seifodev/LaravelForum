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
            ->assertStatus(200);

        $this
            ->assertDatabaseHas('replies', ['body' => $reply->body]);

        $this->assertEquals(1, $thread->fresh()->replies_count);

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
            ->assertStatus(442);

    }

    /**
     * @test
     */
    public function unauthorized_user_can_not_delete_a_reply()
    {
        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertStatus(302)
            ->assertRedirect('/login');

        $this->signIn();
        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertStatus(403);

    }

    /**
     * @test
     */
    public function an_authorized_user_can_delete_his_replies()
    {

        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $response = $this->json('DELETE', "replies/{$reply->id}");

        $response
            ->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['body' => $reply->body]);

        $this->assertEquals(0, $reply->thread->replies_count);

    }

    /**
     * @test
     */
    public function unauthorized_user_can_not_update_a_reply()
    {
        $reply = create('App\Reply');

        $this->patch("/replies/{$reply->id}")
            ->assertStatus(302)
            ->assertRedirect('/login');

        $this->signIn();
        $this->patch("/replies/{$reply->id}")
            ->assertStatus(442);

    }

    /**
     * @test
     */
    public function an_authorized_user_can_update_his_replies()
    {

        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $newBody = 'Updated Body';
        $response = $this->patch("replies/{$reply->id}", ['body' => $newBody]);

        $response
            ->assertStatus(200);

        $this->assertDatabaseHas('replies', ['body' => $newBody]);

    }

    /**
     * @test
     */
    public function replies_that_contains_spam_may_not_be_added()
    {

        $this->withoutExceptionHandling();

        $this->signIn();
        $thread = create(Thread::class);
        $reply = make(Reply::class, [
            'body'  => 'A spam reply test',
        ]);

//        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(442);

    }

    /**
     * @test
     */
    public function users_may_only_reply_once_per_min()
    {
        $this->withoutExceptionHandling();

        $this->signIn();
        $thread = create(Thread::class);

        $reply = make(Reply::class, [
            'body'  => 'A simple reply',
        ]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(200);

//        $this->expectException('\Illuminate\Auth\Access\AuthorizationException');

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(442);


    }

}
