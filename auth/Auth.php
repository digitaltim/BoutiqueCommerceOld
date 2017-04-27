<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Auth;

use It_All\BoutiqueCommerce\Utilities\Database\QueryBuilder;


/**
* 
*/
class Auth
{
	public function user()
	{
		if (isset($_SESSION['user'])) {
			//grab user by their username
			$q = new QueryBuilder("SELECT id, username, password FROM admins WHERE id = $1 ", $_SESSION['user']);
	        $rs = $q->execute();
	        return pg_fetch_assoc($rs);
	    }
	    return false;
	}

	public function check()
		{
			return isset($_SESSION['user']);
		}	

	public function attempt(string $username, string $password): bool
	{
		// grab user by their username
		$q = new QueryBuilder("SELECT id, username, password FROM admins WHERE username = $1 ", $username);
        $rs = $q->execute();
        $user = pg_fetch_assoc($rs);
        
        // check if user exists
        if (!$user) {
            return false;
        }
        
        // verify password for that user
        if (password_verify($password, $user['password'])) {
        	// set session for that user
        	$_SESSION['user'] = $user['id'];
        	return true;
        }
        return false;
	}

}

