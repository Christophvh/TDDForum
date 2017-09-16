<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FavoritesTest extends TestCase
{
	
	use DatabaseMigrations;
	
	/** @test */
	public function guests_cannot_favorite_anything()
	{
		$reply = create('App\Reply');
		
		$this->withExceptionHandling()->post('replies/' . $reply->id . '/favorites')
			->assertRedirect('/login');
	}
	
	/** @test */
	public function an_authenticated_user_can_favorite_any_reply()
	{
		$this->signIn();
		$reply = create('App\Reply');
		
		$this->post('replies/' . $reply->id . '/favorites');
		
		$this->assertCount(1, $reply->favorites);
	}
	
	/** @test */
	public function an_authenticated_user_can_only_favorite_a_reply_once()
	{
		$this->signIn();
		$reply = create('App\Reply');
		
		$this->post('replies/' . $reply->id . '/favorites');
		$this->post('replies/' . $reply->id . '/favorites');
		
		$this->assertCount(1, $reply->favorites);
	}
}