<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Security\Authorization;

use Psr\Log\InvalidArgumentException;

class AuthorizationService
{
    private $roles;

    public function __construct()
    {
        $this->roles = ['owner', 'director', 'manager', 'shipper', 'admin', 'store', 'bookkeeper'];
    }

    /**
     * @param string $minimumRole
     * @return bool
     *
     *
     */
    public function check(string $minimumRole): bool
    {
        if (!in_array($minimumRole, $this->roles)) {
            throw new InvalidArgumentException("minimumRole $minimumRole must be a valid role");
        }
        if (!isset($_SESSION['user']['role'])) {
            return false;
        }

        $role = $_SESSION['user']['role'];

        if (!in_array($role, $this->roles)) {
            unset($_SESSION['user']); // force logout
            return false;
        }

        if (array_search($role, $this->roles) > $minimumRole) {
            return false;
        }

        return true;

    }
}
