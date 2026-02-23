<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Contracts\Auth\Authenticatable;

class PortalService
{
    public const PORTAL_EMPLOYEE = 'employee';
    public const PORTAL_MANAGER = 'manager';
    public const PORTAL_ADMIN = 'admin';

    /**
     * Role names that map to each portal. Department roles use Role.role_type instead.
     */
    protected static array $roleNameToPortal = [
        'Employee' => self::PORTAL_EMPLOYEE,
        'HR Employee' => self::PORTAL_EMPLOYEE,
        'HR Manager' => self::PORTAL_MANAGER,
        'Manager' => self::PORTAL_MANAGER,
        'Super Admin' => self::PORTAL_ADMIN,
        'HR Admin' => self::PORTAL_ADMIN,
        'Finance' => self::PORTAL_ADMIN,
        'Recruiter' => self::PORTAL_ADMIN,
    ];

    protected static array $roleTypeToPortal = [
        Role::ROLE_TYPE_EMPLOYEE => self::PORTAL_EMPLOYEE,
        Role::ROLE_TYPE_MANAGER => self::PORTAL_MANAGER,
        Role::ROLE_TYPE_ADMIN => self::PORTAL_ADMIN,
    ];

    /**
     * Get all portal keys the user has access to (based on their roles).
     *
     * @return array<int, string>
     */
    public function getAvailablePortalsForUser(?Authenticatable $user): array
    {
        if (! $user || ! method_exists($user, 'roles')) {
            return [];
        }

        $portals = [];
        foreach ($user->roles as $role) {
            $portal = self::$roleNameToPortal[$role->name] ?? null;
            if (! $portal && $role instanceof Role && $role->role_type) {
                $portal = self::$roleTypeToPortal[$role->role_type] ?? null;
            }
            if ($portal && ! in_array($portal, $portals, true)) {
                $portals[] = $portal;
            }
        }

        return array_values($portals);
    }

    /**
     * Get the dashboard route for a portal.
     */
    public function getDashboardRouteForPortal(string $portal): string
    {
        return match ($portal) {
            self::PORTAL_EMPLOYEE => 'ess.dashboard',
            self::PORTAL_MANAGER, self::PORTAL_ADMIN => 'dashboard',
            default => 'dashboard',
        };
    }

    /**
     * Get a human-readable label for the portal (for UI).
     */
    public function getPortalLabel(string $portal): string
    {
        return match ($portal) {
            self::PORTAL_EMPLOYEE => __('messages.portal_employee'),
            self::PORTAL_MANAGER => __('messages.portal_manager'),
            self::PORTAL_ADMIN => __('messages.portal_admin'),
            default => $portal,
        };
    }

    /**
     * Get short description for the portal (for choose-portal page).
     */
    public function getPortalDescription(string $portal): string
    {
        return match ($portal) {
            self::PORTAL_EMPLOYEE => __('messages.portal_employee_desc'),
            self::PORTAL_MANAGER => __('messages.portal_manager_desc'),
            self::PORTAL_ADMIN => __('messages.portal_admin_desc'),
            default => '',
        };
    }

    /**
     * Check if the given portal is valid for the user (they have access).
     */
    public function userHasPortal(?Authenticatable $user, string $portal): bool
    {
        return in_array($portal, $this->getAvailablePortalsForUser($user), true);
    }
}
