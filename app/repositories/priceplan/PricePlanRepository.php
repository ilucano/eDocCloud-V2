<?php namespace Repositories\PricePlan;

use PricePlan;
use PricePlanUserTier;
use PricePlanStorageTier;
use PricePlanOwnScanTier;
use PricePlanPlanScanTier;
use Company;

class PricePlanRepository implements PricePlanRepositoryInterface
{
    public function getPricePlans()
    {
        return PricePlan::all();
    }

    public function getPricePlanById($id)
    {

        $pricePlan = $this->queryPricePlan($id);
        $pricePlan->plan_user_tiers = $this->queryPricePlanUserTiers($id);
        $pricePlan->plan_storage_tiers = $this->queryPricePlanStorageTiers($id);
        $pricePlan->plan_own_scan_tiers = $this->queryPricePlanOwnScanTiers($id);
        $pricePlan->plan_plan_scan_tiers = $this->queryPricePlanPlanScanTiers($id);
        return $pricePlan;
    }


    public function getCompanyPricePlan($companyId)
    {

        
    }

    public function createPricePlan(array $data)
    {
       
        try {
            $pricePlanId = $this->insertPricePlan($data);

            if ($pricePlanId) {
                $this->insertPricePlanUserTiers($data, $pricePlanId);
                $this->insertPriceStorageTiers($data, $pricePlanId);
                $this->insertPricePlanOwnScanTiers($data, $pricePlanId);
                $this->insertPricePlanPlanScanTiers($data, $pricePlanId);

            }
            return true;
        } catch (Exception $e) {
            return false;
        }


    }

    private function insertPricePlan($data)
    {
       
        $pricePlan = new PricePlan;

        $pricePlan->plan_code = $data['plan_code'];
        $pricePlan->plan_name = $data['plan_name'];
        $pricePlan->base_price = $data['base_price'];
        $pricePlan->free_users = $data['free_users'];
        $pricePlan->free_gb = $data['free_gb'];
        $pricePlan->free_own_scans = $data['free_own_scans'];
        $pricePlan->free_plan_scans = $data['free_plan_scans'];

        $pricePlan->save();

        return $pricePlan->id;
    }

    private function insertPricePlanUserTiers($data, $pricePlanId)
    {
        foreach ($data['user_to'] as $key => $val) {
            if ($val) {
                $record = new PricePlanUserTier;
                $record->plan_id = $pricePlanId;
                $record->user_to = $val;
                $record->price_per_user = $data['price_per_user'][$key];
                $record->save();
            }
        }
    }

    private function insertPriceStorageTiers($data, $pricePlanId)
    {
        foreach ($data['gb_to'] as $key => $val) {
            if ($val) {
                $record = new PricePlanStorageTier;
                $record->plan_id = $pricePlanId;
                $record->gb_to = $val;
                $record->price_per_gb = $data['price_per_gb'][$key];
                $record->save();
            }
        }

    }

    private function insertPricePlanOwnScanTiers($data, $pricePlanId)
    {
        foreach ($data['own_scan_to'] as $key => $val) {
            if ($val) {
                $record = new PricePlanOwnScanTier;
                $record->plan_id = $pricePlanId;
                $record->own_scan_to = $val;
                $record->price_per_own_scan = $data['price_per_own_scan'][$key];
                $record->save();
            }
        }
    }

    private function insertPricePlanPlanScanTiers($data, $pricePlanId)
    {
        foreach ($data['plan_scan_to'] as $key => $val) {
            if ($val) {
                $record = new PricePlanPlanScanTier;
                $record->plan_id = $pricePlanId;
                $record->plan_scan_to = $val;
                $record->price_per_plan_scan = $data['price_per_plan_scan'][$key];
                $record->save();
            }
        }
    }

    private function queryPricePlan($id)
    {
        return PricePlan::find($id);
    }

    private function queryPricePlanUserTiers($pricePlanId)
    {
        return PricePlanUserTier::where('plan_id', '=', $pricePlanId)
                                  ->orderBy('user_to')
                                  ->get();
    }


    private function queryPricePlanStorageTiers($pricePlanId)
    {
        return PricePlanStorageTier::where('plan_id', '=', $pricePlanId)
                                  ->orderBy('gb_to')
                                  ->get();
    }

    private function queryPricePlanOwnScanTiers($pricePlanId)
    {
        return PricePlanOwnScanTier::where('plan_id', '=', $pricePlanId)
                                  ->orderBy('own_scan_to')
                                  ->get();
    }

    private function queryPricePlanPlanScanTiers($pricePlanId)
    {
        return PricePlanPlanScanTier::where('plan_id', '=', $pricePlanId)
                                  ->orderBy('plan_scan_to')
                                  ->get();
    }


    public function updatePricePlan($id, array $data)
    {
        try {
            $pricePlan = PricePlan::find($id);

            $pricePlan->plan_code = isset($data['plan_code']) ? $data['plan_code']: '';
            $pricePlan->plan_name = isset($data['plan_name']) ? $data['plan_name']: '';
            $pricePlan->base_price = $data['base_price'];
            $pricePlan->free_users = $data['free_users'];
            $pricePlan->free_gb = $data['free_gb'];
            $pricePlan->free_own_scans = $data['free_own_scans'];
            $pricePlan->free_plan_scans = $data['free_plan_scans'];

            $pricePlan->save();

            $this->deletePricePlanUserTiers($id);
            $this->insertPricePlanUserTiers($data, $id);
            
            $this->deletePriceStorageTiers($id);
            $this->insertPriceStorageTiers($data, $id);

            $this->deletePricePlanOwnScanTiers($id);
            $this->insertPricePlanOwnScanTiers($data, $id);

            $this->deletePricePlanPlanScanTiers($id);
            $this->insertPricePlanPlanScanTiers($data, $id);
            
            return true;

        } catch (Exception $e) {
            return false;
        }



    }

    private function deletePricePlanUserTiers($id)
    {
        $records = PricePlanUserTier::where('plan_id', '=', $id);
        $records->delete();
    }

    private function deletePriceStorageTiers($id)
    {
        $records = PricePlanStorageTier::where('plan_id', '=', $id);
        $records->delete();
    }


    private function deletePricePlanOwnScanTiers($id)
    {
        $records = PricePlanOwnScanTier::where('plan_id', '=', $id);
        $records->delete();
    }

    private function deletePricePlanPlanScanTiers($id)
    {
        $records = PricePlanPlanScanTier::where('plan_id', '=', $id);
        $records->delete();
    }


    public function getCompanyWithoutPlan()
    {
        $companiesHasPlan = PricePlan::whereNotNull('company_id')
                                     ->get(['company_id'])->toArray();

        $filterArray = array();
        foreach ($companiesHasPlan as $company) {
            $filterArray[] = $company['company_id'];
        }


        $companies = Company::whereNotIn('row_id', $filterArray)
                              ->orderBy('company_name')
                              ->get();
        return $companies;
    }

    public function assignPlanToCompany($id, $companyId)
    {
        try {
            $originalPlan = $this->queryPricePlan($id)->toArray();

            $newPlanId = $this->copyPlanToCompany($originalPlan, $companyId);

            $userTiers = $this->queryPricePlanUserTiers($id)->toArray();
            $data = $this->convertUserTiersToFormData($userTiers);
            $this->insertPricePlanUserTiers($data, $newPlanId);

            $storageTiers = $this->queryPricePlanStorageTiers($id)->toArray();
            $data = $this->convertStorageTiersToFormData($storageTiers);
            $this->insertPriceStorageTiers($data, $newPlanId);

            $ownScanTiers = $this->queryPricePlanOwnScanTiers($id)->toArray();
            $data = $this->convertOwnScanTiersToFormData($ownScanTiers);
            $this->insertPricePlanOwnScanTiers($data, $newPlanId);

            $planScanTiers = $this->queryPricePlanPlanScanTiers($id)->toArray();
            $data = $this->convertPlanScanTiersToFormData($planScanTiers);
            $this->insertPricePlanPlanScanTiers($data, $newPlanId);

            return $newPlanId;
        } catch (Exception $e) {
            return false;
        }

    }

    private function convertUserTiersToFormData($array)
    {
        $data = array();

        foreach ($array as $key => $item) {
            $data['user_to'][$key] = $item['user_to'];
            $data['price_per_user'][$key] = $item['price_per_user'];
        }

        return $data;
    }

    private function convertStorageTiersToFormData($array)
    {
        $data = array();

        foreach ($array as $key => $item) {
            $data['gb_to'][$key] = $item['gb_to'];
            $data['price_per_gb'][$key] = $item['price_per_gb'];
        }

        return $data;

    }

    private function convertOwnScanTiersToFormData($array)
    {
        $data = array();

        foreach ($array as $key => $item) {
            $data['own_scan_to'][$key] = $item['own_scan_to'];
            $data['price_per_own_scan'][$key] = $item['price_per_own_scan'];
        }

        return $data;

    }

    private function convertPlanScanTiersToFormData($array)
    {
        $data = array();

        foreach ($array as $key => $item) {
            $data['plan_scan_to'][$key] = $item['plan_scan_to'];
            $data['price_per_plan_scan'][$key] = $item['price_per_plan_scan'];
        }

        return $data;

    }

    private function copyPlanToCompany(array $originalPlan, $companyId)
    {
        $pricePlan = new PricePlan;
        $pricePlan->plan_code = '';
        $pricePlan->plan_name = '';
        $pricePlan->base_price = $originalPlan['base_price'];
        $pricePlan->free_users = $originalPlan['free_users'];
        $pricePlan->free_gb = $originalPlan['free_gb'];
        $pricePlan->free_own_scans = $originalPlan['free_own_scans'];
        $pricePlan->free_plan_scans = $originalPlan['free_plan_scans'];
        $pricePlan->is_template = 0;
        $pricePlan->copy_from_template = $originalPlan['id'];
        $pricePlan->company_id = $companyId;
        $pricePlan->save();

        return $pricePlan->id;
    }

    public function getTemplatePricePlans()
    {
        return PricePlan::where('is_template', '=', 1)->get();
    }

    public function getCompanyPricePlans()
    {
        $plans = PricePlan::whereNotNull('company_id')->get();

        foreach ($plans as $plan) {
            $company = Company::where('row_id', '=', $plan->company_id)->first();
            $plan->company_name = $company->company_name;
        }

        return $plans;
    }
}
