<?php namespace Repositories\MonthlyUsageReport;

interface MonthlyUsageReportRepositoryInterface
{
    public function generateDailyReport($companyId, $reportDate);

    public function getCurrentUsageReport($companyId = null);

    public function getDailyDetailsReport($companyId, $toDate);
}
