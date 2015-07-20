<?php namespace Repositories\PricePlan;

class PricePlanRepository implements PricePlanRepositoryInterface
{
    public function getPricePlans()
    {
        return ['test'];
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
        print_r($pricePlanId);
        exit;

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
}
