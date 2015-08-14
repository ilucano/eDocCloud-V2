<?php
namespace Services\MonthlyUsageReport;
 
use Illuminate\Support\ServiceProvider;
 
class MonthlyUsageReportServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Repositories\MonthlyUsageReport\MonthlyUsageReportRepositoryInterface', 'Repositories\MonthlyUsageReport\MonthlyUsageReportRepository');
    }
}
