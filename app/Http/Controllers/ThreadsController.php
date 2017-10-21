<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Http\Requests\StoreThreadRequest;
use App\Thread;
use App\User;
use Fixtures\Prophecy\WithReturnTypehints;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{
	
	/**
	 * ThreadsController constructor.
	 */
	public function __construct()
	{
		$this->middleware('auth')
		     ->except(['index', 'show']);
	}
	
	
	/**
	 * Display a listing of the resource.
	 *
	 * @param Channel $channel
	 *
	 * @param ThreadFilters $filters
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Channel $channel, ThreadFilters $filters)
	{
		$threads = $this->getThreads($channel, $filters);
		
		if( request()->wantsJson()) {
			return $threads;
		}
		
		return view('threads.index', compact('threads'));
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('threads.create');
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param StoreThreadRequest|Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreThreadRequest $request)
	{
		$thread = new Thread(
			[
				'title' => $request->input('title'),
				'body' => $request->input('body'),
			]
		);
		
		$thread->creator()->associate(auth()->id());
		$thread->channel()->associate($request->input('channel_id'));
		$thread->save();
		
		return redirect(action('ThreadsController@show', [$thread->channel, $thread->id]));
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param Channel $channel
	 * @param  \App\Thread $thread
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Channel $channel, Thread $thread)
	{
		$replies = $thread->replies()->paginate(20);
		
		return view('threads.show', compact('thread','replies'));
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Thread $thread
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Thread $thread)
	{
		//
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \App\Thread $thread
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Thread $thread)
	{
		//
	}
    
    /**
     * Remove the specified resource from storage.
     *
     * @param Channel $channel
     * @param  \App\Thread $thread
     *
     * @return \Illuminate\Http\Response
     */
	public function destroy(Channel $channel, Thread $thread)
	{
		$thread->delete();
		if(request()->wantsJson()){
            return response([], 204);
        }
        
        return redirect('/threads');
	}
	
	/**
	 * @param Channel $channel
	 * @param ThreadFilters $filters
	 *
	 * @return mixed
	 */
	private function getThreads(Channel $channel, ThreadFilters $filters)
	{
		$threads = Thread::filters($filters)->latest();
		if ($channel->exists) {
			$threads->where('channel_id', $channel->id);
		}
		$threads = $threads->get();
		
		return $threads;
	}
}
