<?php namespace Repositories\PricePlan;

use PricePlan;
use PricePlanUserTier;
use PricePlanStorageTier;
use PricePlanOwnScanTier;
use PricePlanPlanScanTier;

class PricePlanRepository implements PricePlanRepositoryInterface
{
    public function getPricePlans()
    {
        return PricePlan::all();
    }

    public function getPricePlanById($id)
    {


    }


    public function getCompanyPricePlan($companyId)
    {

        
    }

    public function createPricePlan(array $data)
    {
       
        $pricePlanId = $this->insertPricePlan($data);

        if ($pricePlanId) {
            $this->insertPricePlanUserTiers($data, $pricePlanId);
            $this->insertPriceStorageTiers($data, $pricePlanId);
            $this->insertPricePlanOwnScanTiers($data, $pricePlanId);
            $this->insertPricePlanPlanScanTiers($data, $pricePlanId);

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
}
