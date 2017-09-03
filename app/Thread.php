<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
	
	protected $fillable = [
		'title', 'body', 'user_id', 'channel_id',
	];
	
	
	/**
	 * Add a the replies count to every thread query, Global Scope
	 */
	protected static function boot()
	{
		parent::boot();
		
		static::addGlobalScope('replyCount', function($builder) {
			$builder->withCount('replies');
		});
	}
	
	/**
	 * RELATIONSHIPS
	 * /**
	 * A Thread can have many replies
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function replies()
	{
		return $this->HasMany(Reply::class);
	}
	
	/**
	 * A Thread belongs to its owner
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function creator()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
	
	/**
	 * A Thread belongs to a channel
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function channel()
	{
		return $this->belongsTo(Channel::class);
	}
	
	/**
	 * PUBLIC
	 */
	
	/**
	 * Add a reply to this thread
	 *
	 * @param $reply
	 */
	public function addReply($reply)
	{
		$this->replies()->create($reply);
	}
	
	/**
	 * Apply the thread filters
	 *
	 * @param $query
	 * @param $filters
	 *
	 * @return mixed
	 */
	public function scopeFilters($query, $filters)
	{
		return $filters->apply($query);
	}
	
}
