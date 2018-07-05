<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Activity;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_records_activity_when_a_thread_is_created()
    {
        $this->signIn();
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->assertDatabaseHas('activities', [
            'user_id'   => auth()->id(),
            'type'      => 'created_thread',
            'subject_id'    => $thread->id,
            'subject_type'  => 'App\Thread'
        ]);

        $activity = \App\Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /**
     * @test
     */
    public function it_records_activity_when_reply_is_created()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->assertDatabaseHas('activities', [
            'user_id'       => auth()->id(),
            'type'          => 'created_reply',
            'subject_id'    => $reply->id,
            'subject_type'  => 'App\Reply',
        ]);

        $activity = \App\Activity::all();

        $this->assertCount(2, $activity);
    }

    /**
     * @test
     */
    public function delete_the_activity_when_the_subject_is_deleted()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $this->assertDatabaseHas('activities', ['subject_id' => $thread->id]);

        $thread->delete();
        $this->assertDatabaseMissing('activities', ['subject_id' => $thread->id]);
    }

    /**
     * @test
     */
    public function it_fetches_a_feed_for_any_user()
    {
        $this->signIn();

        create('App\Thread', ['user_id' => auth()->id()], 2);


        // \Illuminate\Support\Carbon::now()->subWeek()

        auth()->user()->activities()->find(2)->update(['created_at' => Carbon::now()->subWeek()]);


        $feed = Activity::feed(auth()->user());

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));
        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));

    }
}
