<?php

use Repositories\MonthlyUsageReport\MonthlyUsageReportRepositoryInterface;

class BillingController extends \BaseController
{
    public function __construct(MonthlyUsageReportRepositoryInterface $repo)
    {
        $this->monthly_usage_report_repo = $repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        $reports = $this->monthly_usage_report_repo->getCurrentUsageReport();
        
        return View::make('billing.index', compact(['reports']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


    public function dailyReport($companyId, $toDate)
    {
        echo $companyId;
        echo $toDate;

    }
}
