<?php
namespace Services\MetaAttribute;
 
use Illuminate\Support\ServiceProvider;
 
class MetaAttributeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Repositories\MetaAttribute\MetaAttributeRepositoryInterface', 'Repositories\MetaAttribute\MetaAttributeRepository');
    }
}
