<?php

namespace Tests\Unit;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class ThreadTest extends TestCase
{

    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        /** @var Thread $this->thread */
        $this->thread = create('App\Thread');

    }

    /** @test */
    function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /** @test */
    function a_thread_has_a_owner()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);
    }

    /** @test */
    function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body'    => 'Testing Body',
            'user_id' => 1,
        ]);

        $this->assertCount(1, $this->thread->replies);
    }
}