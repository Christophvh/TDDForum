<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends QueryFilters {
	
	
	/**
	 * Filter by username
	 *
	 * @param $username
	 *
	 * @return mixed
	 */
	public function by($username)
	{
		$user = User::where('name', $username)->firstOrFail();
		return $this->builder->where('user_id', $user->id);
	}
	
	/**
	 * Filter the query according to most popular threads
	 *
	 * @return mixed
	 */
	public function popular()
	{
		return $this->builder->orderBy('replies_count', 'desc');
	}

}