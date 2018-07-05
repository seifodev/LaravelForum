<?php

namespace Tests\Feature;

use App\Activity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function a_user_can_browse_a_profile()
    {
        // /profiles/user

        $this->withoutExceptionHandling();
        $user = create('App\User');

        $response = $this->get($user->profilePath());

        $response
            ->assertSee($user->name);
    }

    /**
     * @test
     */
    public function profiles_display_all_threads_created_by_a_user()
    {

        $this->withoutExceptionHandling();

        $user = create('App\User');
        $this->signIn($user);

        $firstThreadByJohn = create('App\Thread', ['user_id' => auth()->id()]);
        $secondThreadByJohn = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByJohn = create('App\Thread');

        Activity::find(3)->update(['user_id' => 2]);

        $response = $this->get($user->profilePath());

        $response
            ->assertStatus(200)
            ->assertSee($firstThreadByJohn->title)
            ->assertSee($secondThreadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);

    }
}
