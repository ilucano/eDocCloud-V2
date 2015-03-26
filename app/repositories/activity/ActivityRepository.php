<?php namespace Repositories\Activity;

use Activity;

class ActivityRepository implements ActivityRepositoryInterface
{

    public function getAll()
    {
        return ['a', 'b', 'c'];
    }

    public function getByCompany($companyId)
    {

    }

    public function getByUsers(array $users)
    {

    }

    public function find($id)
    {

    }
}
