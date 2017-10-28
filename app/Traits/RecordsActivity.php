<?php

namespace App\Traits;

use App\Activity;
use App\Thread;

trait RecordsActivity {
    
    protected static function bootRecordsActivity()
    {
        if(auth()->guest()) return ;
        
        foreach (static::getActivitiesToRecord() as $event){
            static::$event(function($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }
    
    /**
     * Specify which events should be recorded
     * @return array
     */
    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }
    
    /**
     * Generate a new activity
     * @param $event
     */
    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);
    }
    
    /**
     * Define the polymorphic relationship
     * @return mixed
     */
    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
    
    /**
     * Get the activity Type
     * @param $event
     *
     * @return string
     */
    protected function getActivityType($event)
    {
        $type =  strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }
}