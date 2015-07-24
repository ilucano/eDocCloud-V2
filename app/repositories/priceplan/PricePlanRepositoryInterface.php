<?php namespace Repositories\PricePlan;

interface PricePlanRepositoryInterface
{

    public function getPricePlans();

    public function getPricePlanById($id);

    public function getCompanyPricePlan($companyId);

    public function createPricePlan(array $data);

    public function updatePricePlan($id, array $data);

    public function getCompanyWithoutPlan();
}
