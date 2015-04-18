<?php
namespace Services\Filemark;
 
use Illuminate\Support\ServiceProvider;
 
class FilemarkServiceProvider extends ServiceProvider {
	
	public function register()
	{
		$this->app->bind('Repositories\Filemark\FilemarkRepositoryInterface', 'Repositories\Filemark\FilemarkRepository');
	}
}