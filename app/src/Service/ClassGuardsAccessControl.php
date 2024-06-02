<?php

namespace App\Service;

class ClassGuardsAccessControl
{
    public function guardAgainstEditListWithoutPermission($list)
    {
        if (!$list) {
            throw new \Exception('You haven\'t permission to access that route.', 403);
        }
        return true;
    }
}