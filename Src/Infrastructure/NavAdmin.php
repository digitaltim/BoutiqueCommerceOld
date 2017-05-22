<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure;

use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Postgres;

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

    private function setSections()
    {
        global $config;

        $this->sections = [
            'Admin Users' => [
                'link' => '/CRUD/admins',
                'subSection' => [
                    'Add New Admin' => [
                        'link' => '/'.$config['dirs']['admin'].'/admins/insert'
                    ]
                ]
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
