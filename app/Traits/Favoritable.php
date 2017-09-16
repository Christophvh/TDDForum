<?php

namespace App\Traits;

use App\Favorite;

trait Favoritable {
	
	/**
	 * The favorited replies
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 */
	public function favorites()
	{
		return $this->morphMany(Favorite::class, 'favorited');
	}
	
	/*
	 * Favorite a reply
	 */
	public function favorite()
	{
		$attributes = ['user_id' => auth()->id()];
		
		if(!$this->favorites()->where($attributes)->exists())
		{
			return $this->favorites()->create($attributes);
		}
	}
	
	/**
	 * Check if a reply is favorited
	 *
	 * @return bool
	 */
	public function isFavorited()
	{
		return !! $this->favorites->where('user_id', auth()->id())->count();
	}
	
	/**
	 * Get the favorites count on a reply
	 *
	 * @return mixed
	 */
	public function getFavoritesCountAttribute()
	{
		return $this->favorites->count();
	}
}