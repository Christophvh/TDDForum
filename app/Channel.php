<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    public function getRouteKeyName()
    {
    	return 'slug';
    }
	
	/**
	 * A channel can have many threads
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
    public function threads()
    {
    	return $this->hasMany(Thread::class);
    }
}

