<?php

namespace App;

trait RecordsActivity
{
    // TODO:: check this out
    protected static function bootRecordsActivity()
    {
        if(auth()->guest()) return;

        foreach(static::getActivitiesToRecord() as $event)
        {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function ($model) {
            $model->activity()->delete();
        });

    }

    protected static function getActivitiesToRecord()
    {
        return ['created', 'updated'];
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id'   => auth()->id(),
            'type'      => $this->getActivityType($event)
        ]);
    }

    public function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }
}