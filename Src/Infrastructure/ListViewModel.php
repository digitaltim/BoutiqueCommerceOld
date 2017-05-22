<?php
declare(strict_types=1);

/*
 * Interface to provide and API to the getRecords method.
 * https://www.sitepoint.com/community/t/mvc-vs-pac/28490/3
 */

namespace It_All\BoutiqueCommerce\Src\Infrastructure;

interface ListViewModel {
    public function getRecords();
}
