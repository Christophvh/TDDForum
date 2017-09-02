<?php

namespace Tests\Feature;

use App\Http\Controllers\ThreadsController;
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
		$this->signIn();
		$thread = create( 'App\Thread' );
		
		// When the user adds a reply to the thread
		$reply = make( 'App\Reply' );
		$this->post( action( 'RepliesController@store', [ $thread->channel, $thread ] ), $reply->toArray() );
		
		// Then their reply should be visible on the page
		$this->get( action( 'ThreadsController@show', [ $thread->channel, $thread ] ) )->assertSee( $reply->body );
	}
	
	/** @test */
	function a_reply_requires_a_body()
	{
		$this->withExceptionHandling()->signIn();
		
		$thread = create( 'App\Thread' );
		$reply  = make( 'App\Reply', [ 'body' => null ] );
		
		$this->post( action( 'RepliesController@store', [
			$thread->channel,
			$thread,
		] ), $reply->toArray() )->assertSessionHasErrors( 'body' );
		
	}
}
