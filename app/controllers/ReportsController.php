<?php

use Repositories\File\FileRepositoryInterface;


class ReportsController extends \BaseController
{	

	 public function __construct(FileRepositoryInterface $filerepo)
    {
        $this->filerepo = $filerepo;

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        echo 'nothing';
    }

   /**
    * Display data of all workflow records
    * except status 1, 16, 17.
    */
   public function showAllBoxes()
   {
    	$workflows = Workflow::whereNotIn('fk_status', array(1, 16, 17))
                             ->get();

       foreach ($workflows as $key => $workflow) {
           //get status

         try {
             $workflow->status = WorkflowStatus::where('row_id', '=', $workflow->fk_status)->firstOrFail()->status;

             $_pickup = Pickup::where('fk_barcode', '=', $workflow->wf_id)->firstOrFail();

             $_object = Object::where('row_id', '=', $_pickup->fk_box)->firstOrFail();

             $workflow->boxid = $_object->row_id;

             $workflow->company_name = Company::where('row_id', '=', $_object->fk_company)->firstOrFail()->company_name;

             try {
                 $workflow->attach = Attach::where('fk_obj_id', '=', $workflow->boxid)->select('row_id', 'attach_name')->firstOrFail();
             } catch (Exception $e) {
                 $workflow->attach = null;
             }
         } catch (Exception $e) {
             //no complete data found, remove it from result!
            unset($workflows[$key]);
         }
       }

      // load the view and pass the data
      return View::make('reports.allboxes')
                   ->with('workflows', $workflows);
   }

   /**
    * Display aggerate reports of boxes group by
    * status and company.
    */
   public function showGroupByStatus()
   {
       $results = DB::table('workflow')
                           ->join('wf_status', 'workflow.fk_status', '=', 'wf_status.row_id')
                           ->join('pickup', 'workflow.wf_id', '=', 'pickup.fk_barcode')
                           ->join('objects', 'pickup.fk_box', '=', 'objects.row_id')
                           ->join('objects as t5', 'objects.fk_parent', '=', 't5.row_id')
                           ->where('objects.fk_obj_type', '=', 2)
                           ->select(DB::raw('workflow.*, (objects.fk_company) as fk_company, (wf_status.status) as status, (COUNT(status)) as qty, (SUM(objects.qty)) as suma, (AVG(t5.ppc)) as precio'))
                           ->groupBy('wf_status.status', 'objects.fk_company')
                           ->orderBy('workflow.fk_status', 'asc')
                           ->get();

       foreach ($results as $result) {
           //get company name and etc
        try {
            $result->company_name = Company::where('row_id', '=', $result->fk_company)->firstOrFail()->company_name;
        } catch (Exception $e) {
            $result->company_name = '';
        }

           if ($result->suma > 0) {
               $result->amount = $result->suma  * $result->precio;
           } else {
               try {
                   $object = DB::table('objects')->select(DB::raw('count(*) as object_count, SUM(qty) as object_sum'))
                                              ->where('fk_obj_type', '=', 2)
                                              ->where('fk_status', '=', 5)
                                              ->where('fk_company', '=', $result->fk_company)
                                              ->first();

                   $result->amount = ((($object->object_sum / $object->object_count) * $result->precio) * $result->qty);
               } catch (Exception $e) {
                    $result->amount = 0;
                   Log::error($e->getMessage());
               }
           }
       }

       // load the view and pass the data
      return View::make('reports.groupbystatus')
                   ->with('results', $results);
   }

    public function showDataUsage()
    {	
    	 

    	$companies = Company::all();
    	
    	$thisMonth= date("Y-m-01 00:00:00");

    	foreach ($companies as $company) {
    		$company->monthly_data_usage = $this->filerepo->getDataUsage($company->row_id, $thisMonth);
    		$company->todate_data_usage = $this->filerepo->getDataUsage($company->row_id);
    		$company->number_of_files = $this->filerepo->getNumberOfFiles($company->row_id);
    	}
    	
        return View::make('reports.datausage')
        			 ->with('companies', $companies);
    }

    public function showUsageChart($companyId)
    {
      
      $company = Company::where('row_id', '=', $companyId)->first();
     
      $company->todate_data_usage = $this->filerepo->getDataUsage($company->row_id);

    
      $company->monthly_data_usage = $this->filerepo->getMonthlyDataUsage($company->row_id);
      $company->monthly_number_of_files = $this->filerepo->getMonthlyNumberOfFiles($company->row_id);
  
      return View::make('reports.usagechart')
                  ->with('company', $company);


    }
}
