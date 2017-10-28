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
      'user_id', 'type', 'subject_id', 'subject_type'
    ];
    
    public function subject()
    {
        return $this->morphTo();
    }
}
