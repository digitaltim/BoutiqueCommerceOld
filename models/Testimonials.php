<?php
declare(strict_types=1);

/*
 * Domain model provides generic accessors to domain state.
 * https://www.sitepoint.com/community/t/mvc-vs-pac/28490/3
 */

namespace It_All\BoutiqueCommerce\Models;

use It_All\BoutiqueCommerce\Postgres;

class Testimonials extends DbTable
{
    function __construct(Postgres $db)
    {
        parent::__construct('testimonials', $db);
    }

    public function findById(int $id)
    {
        return pg_fetch_assoc($this->select('*', ['id' => $id]));
    }

    public function findAll() {
        $rows = [];
        $rs = $this->select();
        while ($row = pg_fetch_assoc($rs)) {
            $rows[] = $row;
        }
        return $rows;
    }
}
