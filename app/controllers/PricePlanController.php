<?php

use Repositories\PricePlan\PricePlanRepositoryInterface;

class PricePlanController extends \BaseController
{
    public function __construct(PricePlanRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $pricePlans = $this->repo->getTemplatePricePlans();
        $companiesNoPlan = $this->repo->getCompanyWithoutPlan();
        $companyDropdown = array();
        foreach ($companiesNoPlan as $company) {
            $companyDropdown[$company->row_id] = $company->company_name;

        }

        $companyPlans = $this->repo->getCompanyPricePlans();

        return View::make('priceplan.index', compact(['pricePlans', 'companyDropdown', 'companyPlans']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //

        return View::make('priceplan.create', compact(['details', 'var2']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //

        $rules = array(
           'plan_name'             => 'required|unique:price_plans',                        // just a normal required validation
           'plan_code'            => 'required|unique:price_plans',     // required and must be unique in the ducks table
           'base_price'         => 'required|numeric',
           'free_users' => 'required|integer',
           'free_gb' => 'required|integer',
           'free_own_scans' => 'required|integer',
           'free_plan_scans' => 'required|integer',

        );

        foreach (Input::get('user_to') as $key => $val) {
            if ($val) {
                $rules['user_to.'.$key] = 'integer';
                $rules['price_per_user.'.$key] = 'required|numeric';
            }
        }

        foreach (Input::get('gb_to') as $key => $val) {
            if ($val) {
                $rules['gb_to.'.$key] = 'integer';
                $rules['price_per_gb.'.$key] = 'required|numeric';
            }
        }

        foreach (Input::get('own_scan_to') as $key => $val) {
            if ($val) {
                $rules['own_scan_to.'.$key] = 'integer';
                $rules['price_per_own_scan.'.$key] = 'required|numeric';
            }
        }

        foreach (Input::get('plan_scan_to') as $key => $val) {
            if ($val) {
                $rules['plan_scan_to.'.$key] = 'integer';
                $rules['price_per_plan_scan.'.$key] = 'required|numeric';
            }
        }


        $validator = Validator::make(Input::all(), $rules);

        // check if the validator failed -----------------------
        if ($validator->fails()) {
            // get the error messages from the validator
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            return Redirect::route('priceplan.create')
                ->withErrors($validator)
                ->withInput(Input::except('user_to', 'price_per_user', 'gb_to', 'price_per_gb', 'own_scan_to', 'price_per_own_scan', 'plan_scan_to', 'price_per_plan_scan'));
        }

        $result = $this->repo->createPricePlan(Input::all());

        if ($result) {
            Session::flash('message', 'Price plan successfuly created');
            return Redirect::route('priceplan.index');
        } else {
            Session::flash('error', 'Error creating price plan');
            return Redirect::route('priceplan.index');

        }

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
        $pricePlan = $this->repo->getPricePlanById($id);
        $company = '';
        if ($pricePlan->company_id) {
            $company = Company::where('row_id', '=', $pricePlan->company_id)->first();
        }
        
        return View::make('priceplan.edit', compact(['pricePlan', 'company']));

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
        
        $pricePlan = $this->repo->getPricePlanById($id);

        $rules = array(
           'base_price'         => 'required|numeric',
           'free_users' => 'required|integer',
           'free_gb' => 'required|numeric',
           'free_own_scans' => 'required|integer',
           'free_plan_scans' => 'required|integer',

        );

        if ($pricePlan->is_template == 1) {
            $rules['plan_name'] = 'required|unique:price_plans,plan_name,'. $id;
            $rules['plan_code'] = 'required|unique:price_plans,plan_code,'. $id;
        }

        foreach (Input::get('user_to') as $key => $val) {
            if ($val) {
                $rules['user_to.'.$key] = 'integer';
                $rules['price_per_user.'.$key] = 'required|numeric';
            }
        }

        foreach (Input::get('gb_to') as $key => $val) {
            if ($val) {
                $rules['gb_to.'.$key] = 'numeric';
                $rules['price_per_gb.'.$key] = 'required|numeric';
            }
        }

        foreach (Input::get('own_scan_to') as $key => $val) {
            if ($val) {
                $rules['own_scan_to.'.$key] = 'integer';
                $rules['price_per_own_scan.'.$key] = 'required|numeric';
            }
        }

        foreach (Input::get('plan_scan_to') as $key => $val) {
            if ($val) {
                $rules['plan_scan_to.'.$key] = 'integer';
                $rules['price_per_plan_scan.'.$key] = 'required|numeric';
            }
        }


        $validator = Validator::make(Input::all(), $rules);

        // check if the validator failed -----------------------
        if ($validator->fails()) {
            // get the error messages from the validator
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            return Redirect::route('priceplan.edit', ['priceplan' => $id])
                ->withErrors($validator)
                ->withInput(Input::except('user_to', 'price_per_user', 'gb_to', 'price_per_gb', 'own_scan_to', 'price_per_own_scan', 'plan_scan_to', 'price_per_plan_scan'));
        }


        $result = $this->repo->updatePricePlan($id, Input::all());
        
        if ($result == true) {
            Session::flash('message', 'Price plan successfuly updated');
            return Redirect::route('priceplan.edit', ['priceplan' => $id]);
        } else {
            Session::flash('error', 'Error updating price plan');
            return Redirect::route('priceplan.edit', ['priceplan' => $id]);
        }
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


    public function assignPlan()
    {
        $rules = array(
           'assignplan' => 'required|integer',
           'company_id' => 'required|integer',
        );

        $validator = Validator::make(Input::all(), $rules);

        // check if the validator failed -----------------------
        if ($validator->fails()) {
            // get the error messages from the validator
            $messages = $validator->messages();

            // redirect our user back to the form with the errors from the validator
            return Redirect::route('priceplan.index')
                ->withErrors($validator);
        
        }

        $planId = Input::get('assignplan');
        $companyId =  Input::get('company_id');

        $newPricePlanId = $this->repo->assignPlanToCompany($planId, $companyId);

        if ($newPricePlanId) {
            Session::flash('message', 'Price plan successfuly assigned to company.<br/>You can review and make changes here.');
            return Redirect::route('priceplan.edit', ['priceplan' => $newPricePlanId]);
        } else {
            Session::flash('error', 'Error assign price plan');
            return Redirect::route('priceplan.index');
        }
    }
}
