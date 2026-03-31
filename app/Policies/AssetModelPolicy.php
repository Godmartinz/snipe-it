<?php

namespace App\Policies;

use App\Models\User;

class AssetModelPolicy extends SnipePermissionsPolicy
{
    protected function columnName()
    {
        return 'models';
    }

    public function files(User $user, $item = null)
    {
        return $user->hasAccess($this->columnName().'.files');
    }
}
