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
	
	function a_user_can_filter_threads_according_to_a_channel()
	{
		$channel            = create( 'App\Channel' );
		$threadInChannel    = create( 'App\Thread', [ 'channel_id' => $channel->id ] );
		$threadNotInChannel = create( 'App\Thread' );
		
		$this->get( action( 'ThreadsController@show', [ $channel ] ) )
		     ->assertSee( $threadInChannel->title )
		     ->assertSee( $threadNotInChannel->title );
	}
	
	/** @test */
	public function a_user_can_filter_threads_by_any_username()
	{
		$this->signIn(create('App\User', ['name' => 'Jeff']));
		
		$threadByJeff = create('App\Thread', ['user_id' => auth()->id()]);
		$threadNotByJeff = create('App\Thread');
		
		$this->get('threads?by=Jeff')
			->assertSee($threadByJeff->title)
			->assertDontSee($threadNotByJeff->title);
	}
	
	/** @test */
	public function a_user_can_filter_threads_by_popularity()
	{
		// Given we have three threads
		// With 2 replies, 3 replies, and 0 replies, respectively.
		$threadWithTwoReplies = create('App\Thread');
		create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);
		
		$threadWithThreeReplies = create('App\Thread');
		create('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);
		
		$threadWithNoReplies = $this->thread;
		
		// When I filter all threads by popularity
		$response = $this->getJson('threads?popular=1')->json();
		
		// Then they should be returned from most replies to least.
		$this->assertEquals([3,2,0], array_column($response, 'replies_count'));
	}
}
