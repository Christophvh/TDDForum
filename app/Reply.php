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
}
