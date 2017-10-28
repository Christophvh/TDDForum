<?php

namespace App;

use App\Traits\Favoritable;
use App\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
	use Favoritable, RecordsActivity;
    /**
     * The fields that may be mass assigned
     *
     * @var array
     */
    protected $fillable = [
        'body', 'user_id',
    ];
    
    protected $with = ['owner', 'favorites'];

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
	
}
