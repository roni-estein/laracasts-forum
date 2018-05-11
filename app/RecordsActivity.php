<?php

namespace App;


trait RecordsActivity
{

    public static function testStateRetention()
    {
        return auth()->id();
    }

    protected static function bootRecordsActivity()
    {

        //dd(auth());

        if(auth()->guest()) return;

//        dd(static::getActivitiesToRecord());
        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event){
                $model->recordActivity($event);
            });
        }


        static::deleting(function($model){
            $model->activity()->delete();
        });
    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'type' => $this->getActivityType($event),
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Override this method if you want to record activities other than created
     * @return array
     */
    public static function getActivitiesToRecord()
    {
        return ['created'];
    }

    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());

        return "{$event}_{$type}";
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

}