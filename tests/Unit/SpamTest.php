<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use App\Reply;
use App\Inspections\Spam;

class SpamTest extends TestCase
{
    /**
     * @test
     */
    public function it_checks_for_invalid_keywords()
    {
        $spam = new Spam;
        $this->assertFalse($spam->detect('an innocent reply'));

    }

    /**
     * @test
     */
    public function it_checks_for_any_key_being_held_down()
    {
        $this->withoutExceptionHandling();
        $spam = new Spam;

        $this->expectException(\Exception::class);

        $spam->detect('aaaaa');
    }
}
