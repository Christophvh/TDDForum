<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Http\Requests\StoreRepliesRequest;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{

    /**
     * RepliesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
	
	
	/**
	 * Store a reply in storage
	 *
	 * @param StoreRepliesRequest|Request $request
	 * @param Channel $channel
	 * @param Thread $thread
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
    public function store(StoreRepliesRequest $request, Channel $channel, Thread $thread){

        $thread->addReply([
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        return back();
    }
}
