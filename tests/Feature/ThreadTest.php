<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadTest extends TestCase
{
	
	use DatabaseMigrations;
	
	private $thread;
	
	public function setUp()
	{
		parent::setUp();
		
		$this->thread = factory( 'App\Thread' )->create();
		
	}
	
	/** @test */
	public function a_user_can_browse_threads()
	{
		$this->get( '/threads' )->assertSee( $this->thread->title );
	}
	
	/** @test */
	public function a_user_can_browse_a_single_thread()
	{
		$this->get( action( 'ThreadsController@show', [
			$this->thread->channel,
			$this->thread,
		] ) )->assertSee( $this->thread->title );
	}
	
	/** @test */
	public function a_single_thread_should_show_its_associated_replies()
	{
		$reply = create( 'App\Reply', [ 'thread_id' => $this->thread->id ] );
		$this->get( action( 'ThreadsController@show', [
			$this->thread->channel,
			$this->thread,
		] ) )->assertSee( $reply->body );
	}
	
	function a_user_can_filter_threads_according_to_a_tag()
	{
		$channel            = create( 'App\Channel' );
		$threadInChannel    = create( 'App\Thread', [ 'channel_id' => $channel->id ] );
		$threadNotInChannel = create( 'App\Thread' );
		
		$this->get( action( 'ThreadsController@show', [ $channel ] ) )
		     ->assertSee( $threadInChannel->title )
		     ->assertSee( $threadNotInChannel->title );
	}
}
