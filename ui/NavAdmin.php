<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\UI;

/**
 * navigation for admin pages
 */
class NavAdmin
{
    private $sections;
    const NAV_LI_STYLE = 'navListItem';
    const SUBNAV_LI_STYLE = 'subNavListItem';
    const NAV_ITEM_STYLE = 'navItem';
    const SUBNAV_ITEM_STYLE = 'subNavItem';

    function __construct()
    {
        $this->setSections();
        return $this;
    }

    /**
     * permissions should only be set here for external URL links. internal (admin) link permissions are set in Config::setAdminPagePermissions()
     * links should include index.php in order to match Config::$adminPagePermissions
     * no sub-subSections allowed
     */
    private function setSections()
    {
        $this->sections = array(
            'Orders' => array(
                'link' => 'orders/index.php'
            ),
            'Designers' => array(
                'link' => 'designers/index.php'
            ),
            'Testimonials' => array(
                'link' => 'ORM/index.php?t=testimonials',
                'permissions' => 'owner',
                'subSection' => array(
                    'insert' => array(
                        'link' => 'ORM/insert.php?t=testimonials',
                        'permissions' => 'owner'
                    )
                )
            ),
            'Projects' => array(
                'permissions' => 'admin',
                'link' => 'Config::$projectsUrl',
                'linkType' => 'external',
                'target' => '_blank'
            ),
            'Reports' => array(
                'subSection' => array(
                    'Best Customers' => array(
                        'link' => 'reports/BestCustomers.php'
                    )
                )
            ),
            'Admin Users' => array(
                'link' => 'admins/index.php'
            ),
            'ORM' => array(
                'subSection' => array(
                    'ormTables' => null //special case
                )
            )
        );
    }

    public function getSections()
    {
        return $this->sections;
    }
}