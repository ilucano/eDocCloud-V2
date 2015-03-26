<?php
namespace Repositories\Activity;

interface ActivityRepositoryInterface
{
    public function getAll();

    public function getByCompany($companyId);

    public function getByUsers(array $users);

    public function find($id);

}
