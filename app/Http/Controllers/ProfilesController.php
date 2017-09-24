<?php

namespace App\Http\Controllers;

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
        $threads = $user->threads()->paginate(30);
        
        return view('profiles.show', compact('profileUser', 'threads'));
    }
}
