<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Reply;
use App\Thread;

class FavouritesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function guests_can_not_favourite_any_reply()
    {

        $response = $this->post('/replies/1/favourites');
        $response
            ->assertStatus(302)
            ->assertRedirect('/login');

    }

    /**
     * @test
     */
    public function an_authenticated_user_can_favourite_any_reply()
    {

        $this->withoutExceptionHandling();

        $this->signIn();

        $reply = create('App\Reply');

        $response = $this->post('/replies/' . $reply->id . '/favourites');

        $this->assertCount(1, $reply->favourites);

    }

    /**
     * @test
     */
    public function an_authenticated_user_can_only_favourite_a_reply_once()
    {

        $this->signIn();
        $reply = create('App\Reply');

        $this->post('replies/' . $reply->id . '/favourites');
        $this->post('replies/' . $reply->id . '/favourites');

        $this->assertCount(1, $reply->favourites);

    }

    /**
     * @test
     */
    public function an_authenticated_user_can_unfavourite_any_reply()
    {

        $this->withoutExceptionHandling();
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->post('/replies/' . $reply->id . '/favourites');
        $this->assertCount(1, $reply->favourites);

        $this->delete('/replies/' . $reply->id . '/favourites');

        // TODO :: check
        $this->assertCount(0, $reply->fresh()->favourites);

    }
}
