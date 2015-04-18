<?php
namespace Services\Activity;
 
use Illuminate\Support\ServiceProvider;
 
class ActivityServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Repositories\Activity\ActivityRepositoryInterface', 'Repositories\Activity\ActivityRepository');
    }
}
