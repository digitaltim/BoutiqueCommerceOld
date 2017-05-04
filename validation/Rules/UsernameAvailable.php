<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;
use It_All\BoutiqueCommerce\Utilities\Database\QueryBuilder;
/**
*
*/
class UsernameAvailable extends AbstractRule
{

        public function validate($input)
        {
                // grab user by their username
                $q = new QueryBuilder("SELECT username FROM admins WHERE username = $1 ", $input);
        $rs = $q->execute();
        $user = pg_fetch_assoc($rs);

        // check if user exists
        if ($user) {
                return false;
        }
        return true;
        }
}