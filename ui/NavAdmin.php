<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\UI;

use It_All\BoutiqueCommerce\Postgres;

/**
 * navigation for admin pages
 */
class NavAdmin
{
    private $sections;
    private $database;


    const NAV_LI_STYLE = 'navListItem';
    const SUBNAV_LI_STYLE = 'subNavListItem';
    const NAV_ITEM_STYLE = 'navItem';
    const SUBNAV_ITEM_STYLE = 'subNavItem';

    function __construct(Postgres $database)
    {
        $this->database = $database;
        $this->setSections();
        $this->setCrudNavigationSubsections();
        return $this;
    }

    /**
     * permissions should only be set here for external URL links. internal (admin) link permissions are set in Config::setAdminPagePermissions()
     * links should include index.php in order to match Config::$adminPagePermissions
     * no sub-subSections allowed
     */
    private function setSections()
    {
        global $config;

        $this->sections = [
            'Orders' => [
                'link' => '/orders/index.php'
            ],
            'Designers' => [
                'link' => '/designers/index.php'
            ],
            'Testimonials' => [
                'link' => '/ORM/index.php?t=testimonials',
                'permissions' => 'owner',
                'subSection' => [
                    'insert' => [
                        'link' => 'ORM/insert.php?t=testimonials',
                        'permissions' => 'owner'
                    ]
                ]
            ],
            'Projects' => [
                'permissions' => 'admin',
                'link' => $config['projectsUrl'],
                'linkType' => 'external',
                'target' => '_blank'
            ],
            'Reports' => [
                'subSection' => [
                    'Best Customers' => [
                        'link' => '/reports/BestCustomers.php'
                    ]
                ]
            ],
            'Admin Users' => [
                'link' => '/CRUD/admins'
            ],
            'CRUD' => [
                'subSection' => [
                ]
            ]
        ];
    }

    /**
     * Set CRUD navigation subsections
     */
    private function setCrudNavigationSubsections() {
        $ormTables = "";
        $dbTables = array();
        if ($dbTablesRes = $this->database->getSchemaTables()) {
            while ($tableRow = pg_fetch_array($dbTablesRes)) {
                $dbTables[] = $tableRow[0];
            }
        }

        foreach ($dbTables as $table) {
            $this->sections['CRUD']['subSection'][$table]['link'] = '/CRUD/' . $table;
        }
    }

    public function getSections()
    {
        return $this->sections;
    }
}