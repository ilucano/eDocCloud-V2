<?php
namespace Services\File;
 
use Illuminate\Support\ServiceProvider;
 
class FileServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Repositories\File\FileRepositoryInterface', 'Repositories\File\FileRepository');
    }
}
