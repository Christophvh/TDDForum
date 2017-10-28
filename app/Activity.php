<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    
    /**
     * Mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
        'subject_id',
        'subject_type',
    ];
    
    /**
     * Determine the subject object of this activity
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subject()
    {
        return $this->morphTo();
    }
    
    /**
     * Return the activity feed for a given user
     *
     * @param User $user
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Relations\HasMany[]|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public static function feed(User $user)
    {
        return $user->activity()->latest()->with('subject')->get()->groupBy(function ($activity) {
            return $activity->created_at->format('Y-m-d');
        });
    }
}
