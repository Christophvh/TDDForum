<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    
    /**
     * Show the profile of a user
     *
     * @param User $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        $profileUser = $user;
        $activities= Activity::feed($user);
        
        return view('profiles.show', compact('profileUser', 'activities'));
    }
}
