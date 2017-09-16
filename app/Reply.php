<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{

    /**
     * The fields that may be mass assigned
     *
     * @var array
     */
    protected $fillable = [
        'body', 'user_id',
    ];

    /**
     * RELATIONSHIPS
     */

    /**
     * A reply belongs to its owner
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A reply belongs to a thread
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
	
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
}
