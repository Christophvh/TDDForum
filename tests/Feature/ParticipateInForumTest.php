<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{

    use DatabaseMigrations;


    /** @test */
    function an_authenticated_user_may_participate_in_forum_threads()
    {
       $this->be($user = create('App\User'));

        $thread = create('App\Thread');

        // When the user adds a reply to the thread
        $reply = make('App\Reply');
        $this->post('/threads/' . $thread->id . '/replies', $reply->toArray());

        // Then their reply should be visible on the page
        $this->get('/threads/' . $thread->id)->assertSee($reply->body);
    }
}
