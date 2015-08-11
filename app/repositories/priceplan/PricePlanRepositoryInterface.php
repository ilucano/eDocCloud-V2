<?php namespace Repositories\PricePlan;

interface PricePlanRepositoryInterface
{
    public function getPricePlans();

    public function getPricePlanById($id);

    public function getPricePlanByCompanyId($companyId);

    public function createPricePlan(array $data);

    public function updatePricePlan($id, array $data);

    public function getCompanyWithoutPlan();

    public function assignPlanToCompany($id, $companyId);

    public function getTemplatePricePlans();

    public function getCompanyPricePlans();
}
