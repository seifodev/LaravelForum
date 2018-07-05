<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\User;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;
    public function setUp()
    {
        parent::setUp();
        $this->thread = make(Thread::class);
    }

    /**
     * @test
     */
    public function guests_can_not_add_a_thread()
    {

        // Guests can not visit the threads/create page
        $this
            ->get('threads/create')
            ->assertStatus(302)
            ->assertRedirect('/login');

        // Guests can not add a thread at all
        $this
            ->withoutExceptionHandling()
            ->expectException('Illuminate\Auth\AuthenticationException');

        $this->post('/threads', []);
    }


    /**
     * @test
     */
    public function an_authenticated_user_can_add_a_new_thread()
    {

        $response = $this->signIn()
            ->post('/threads', $this->thread->toArray());

        $response->assertStatus(302);

        $this
            ->get($response->headers->get('location'))
            ->assertStatus(200)
            ->assertSee($this->thread->title)
            ->assertSee($this->thread->body);
    }

    /**
     * @test
     */
    public function unauthorized_users_can_not_delete_threads()
    {
        $thread = create(Thread::class);

        $this->delete($thread->path())
            ->assertStatus(302)
            ->assertRedirect('/login');

        $this->signIn();
        $this->delete($thread->path())
            ->assertStatus(403);


    }

    /**
     * @test
     */
    public function an_authorized_user_can_delete_a_thread()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());
        $response->assertStatus(401);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

    }

    /**
     * @test
     */
    public function a_thread_requires_a_title()
    {

        $response = $this->publishThread(['title' => null]);
        $response
            ->assertSessionHasErrors('title');
    }

    /**
     * @test
     */
    public function a_thread_requires_a_body()
    {
        $response = $this->publishThread(['body' => null]);
        $response->assertSessionHasErrors('body');

    }

    /**
     * @test
     */
    public function a_thread_requires_a_valid_channel()
    {

        $response = $this->publishThread(['channel_id' => null]);
        // will pass session error
        $response->assertSessionHasErrors('channel_id');

        // not existed channel_id value 150
        $response = $this->publishThread(['channel_id' => 999]);
        // will pass session error
        $response->assertSessionHasErrors('channel_id');

    }

    /**
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function publishThread($overrides = [])
    {
        $thread = make(Thread::class, $overrides);

        $response =
            $this->signIn()
                 ->post('/threads', $thread->toArray());

        return $response;
    }
}
