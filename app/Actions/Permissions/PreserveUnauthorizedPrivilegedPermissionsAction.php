<?php

namespace App\Actions\Permissions;

use App\Models\User;

final class PreserveUnauthorizedPrivilegedPermissionsAction
{
    /**
     * Preserve privileged permission keys unless the authenticated user may manage them.
     *
     * @param  array<string, mixed>  $requestedPermissions
     * @param  array<string, mixed>  $originalPermissions
     * @return array<string, mixed>
     */
    public static function run(array $requestedPermissions, User $authenticatedUser, array $originalPermissions = [], ?User $targetUser = null): array
    {
        // Disallow non-admin/superuser users from modifying their own permissions, but allow them to modify other users' permissions (except for admin/superuser keys).
        if ($targetUser && ! $authenticatedUser->isSuperUser() && $authenticatedUser->id === $targetUser->id) {
            return $originalPermissions;
        }

        if (! $authenticatedUser->isSuperUser()) {
            if (array_key_exists('superuser', $originalPermissions)) {
                $requestedPermissions['superuser'] = $originalPermissions['superuser'];
            } else {
                unset($requestedPermissions['superuser']);
            }
        }

        if ((! $authenticatedUser->isAdmin()) && (! $authenticatedUser->isSuperUser())) {
            if (array_key_exists('admin', $originalPermissions)) {
                $requestedPermissions['admin'] = $originalPermissions['admin'];
            } else {
                unset($requestedPermissions['admin']);
            }
        }

        return $requestedPermissions;
    }
}
