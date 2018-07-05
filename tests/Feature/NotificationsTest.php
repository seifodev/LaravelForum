<?php

namespace Tests\Feature;

use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        $this->signIn();
    }

    /**
     * @test
     */
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_not_by_the_current_user()
    {
        $thread = create('App\Thread')->subscribe();


        $this->assertCount(0, auth()->user()->notifications);

        // every time a new reply is left

        // A reply by the current user: in this case the current user does not need any notification
        $thread->addReply([
            'user_id'   => auth()->id(),
            'body'      => 'anything'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        // A reply by another user
        $user = create('App\User');

        $reply = $thread->addReply([
            'user_id'   => $user->id,
            'body'      => 'any reply body'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /**
     * @test
     */
    public function a_user_can_fetch_their_unread_notifications()
    {
        create(DatabaseNotification::class);

        $response = $this->get(route('notifications.index', [auth()->user()->name]));

        $this->assertCount(1, $response->json());

    }

    /**
     * @test
     */
    public function a_user_can_mark_a_notification_as_read()
    {
        create(DatabaseNotification::class);

        $this->assertCount(1, auth()->user()->unreadNotifications);


        $this->delete(route('notification.delete', [auth()->user()->name, auth()->user()->unreadNotifications->first()->id]))
            ->assertStatus(200);

        $this->assertCount(0, auth()->user()->fresh()->unreadNotifications);

    }
}
