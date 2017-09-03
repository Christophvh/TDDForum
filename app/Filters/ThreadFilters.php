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

}