<?php
namespace Services\PricePlan;
 
use Illuminate\Support\ServiceProvider;
 
class PricePlanServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Repositories\PricePlan\PricePlanRepositoryInterface', 'Repositories\PricePlan\PricePlanRepository');
    }
}
