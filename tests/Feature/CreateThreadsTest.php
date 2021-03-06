<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadsTest extends TestCase
{
	
	use DatabaseMigrations;
	
	/** @test */
	function a_guest_cant_create_new_forum_threads()
	{
		$this->withExceptionHandling();
		
		$this->get( '/threads/create' )->assertRedirect( '/login' );
		
		$this->post( '/threads' )->assertRedirect( '/login' );
		
	}
	
	/** @test */
	function an_authenticated_user_can_create_new_forum_threads()
	{
		$this->signIn();
		
		$thread = make( 'App\Thread' );
		
		$response = $this->post( '/threads', $thread->toArray() );
		$this->get( $response->headers->get( 'Location' ) )->assertSee( $thread->title )->assertSee( $thread->body );
	}
	
	/** @test */
    function unauthorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();
        $thread = create('App\Thread');
        
        $this->delete( "threads/{$thread->channel->slug}/{$thread->id}")->assertRedirect('/login');
        
        // A logged in user that doesn't own the thread should nog be able to delete it
        $this->signIn();
        $this->delete( "threads/{$thread->channel->slug}/{$thread->id}")->assertStatus(403);
    }
	/** @test */
	function authorized_users_can_delete_threads()
    {
        $this->signIn();
        
        $thread = create('App\Thread',['user_id' => auth()->id()]);
        $channel = create('App\Channel');
        $reply = create('App\Reply', ['thread_id' => $thread->id]);
        
        $response = $this->json('DELETE',"threads/{$channel->slug}/{$thread->id}");
        
        $response->assertStatus(204);
        
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        
        $this->assertDatabaseMissing('activities', [
           'subject_id' => $thread->id,
           'subject_type' => get_class($thread)
        ]);
        
        $this->assertDatabaseMissing('activities', [
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply)
        ]);
    }
    
	/** @test */
	function a_thread_requires_a_title()
	{
		$this->publishThread( [ 'title' => null ] )->assertSessionHasErrors( 'title' );
	}
	
	/** @test */
	function a_thread_requires_a_body()
	{
		$this->publishThread( [ 'body' => null ] )->assertSessionHasErrors( 'body' );
	}
	
	/** @test */
	function a_thread_requires_a_valid_channel()
	{
		factory( 'App\Channel', 2 )->create();
		
		$this->publishThread( [ 'channel_id' => null ] )->assertSessionHasErrors( 'channel_id' );
		
		
		$this->publishThread( [ 'channel_id' => null ] )->assertSessionHasErrors( 'channel_id' );
	}
	
	/**
	 * Helper to simplify the testing for exception handling
	 *
	 * @param array $overrides
	 *
	 * @return \Illuminate\Foundation\Testing\TestResponse
	 */
	protected function publishThread( $overrides = [] )
	{
		$this->withExceptionHandling()->signIn();
		
		$thread = make( 'App\Thread', $overrides );
		
		return $this->post( '/threads', $thread->toArray() );
	}
}
