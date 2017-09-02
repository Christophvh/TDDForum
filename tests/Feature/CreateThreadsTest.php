<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadsTest extends TestCase {

	use DatabaseMigrations;

	/** @test */
	function a_guest_cant_create_new_forum_threads()
	{

		$this->expectException( 'Illuminate\Auth\AuthenticationException' );
		$thread = make( 'App\Thread' );
		$this->post( '/threads', $thread->toArray() );

	}

	/** @test */
	function an_authenticated_user_can_create_new_forum_threads()
	{
		$this->actingAs( create( 'App\User' ));

		$thread = make( 'App\Thread' );
		$this->post( '/threads', $thread->toArray() );

		$this->get( '/threads/' . $thread->id )->assertSee( $thread->title )->assertSee( $thread->body );
	}
}
