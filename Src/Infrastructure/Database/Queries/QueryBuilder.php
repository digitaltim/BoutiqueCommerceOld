<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries;

use function It_All\BoutiqueCommerce\Src\Infrastructure\Utilities\arrayWalkToStringRecursive;

class QueryBuilder
{
    public $sql;
    public $args = array();

    /**
     * QueryBuilder constructor. like add, for convenience
     */
    function __construct() {
        $args = func_get_args();
        // note func_num_args returns 0 if just 1 argument of null passed in
        if (count($args) > 0) {
            call_user_func_array(array($this, 'add'), $args);
        }
    }

    /**
     * appends sql and args to query
     * @param string $sql
     * @return $this
     */
    public function add(string $sql) {
        $args = func_get_args();
        array_shift($args); // drop the first one (the sql string)
        $this->sql .= $sql;
        $this->args = array_merge($this->args, $args);
        return $this;
    }

    /**
     * handle null argument for correct sql
     * @param string $name
     * @param $arg
     * @return $this
     */
    public function null_eq(string $name, $arg) {
        if ($arg === null) {
            $this->sql .= "$name is null";
        }
        else {
            $this->args[] = $arg;
            $argNum = count($this->args);
            $this->sql .= "$name = \$$argNum";
        }
        return $this;
    }

    /**
     * sets sql and args
     * @param string sql
     * @param $args
     */
    public function set(string $sql, array $args) {
        $this->sql = $sql;
        $this->args = $args;
    }

    public function execute() {
        // suppress errors as they are thrown below
        if (!$res = @pg_query_params($this->sql, $this->args)) {
            $msg = $this->sql . " args: [" . arrayWalkToStringRecursive($this->args) . "]";
            throw new \Exception('Query Execution Failure: '.$msg);
        }
        return $res;
    }

    public function executeReturning() {
        $res = $this->execute();
        return pg_fetch_row($res)[0];
    }

    /**
     * returns the value of the one column in one record
     * or false if 0 or multiple records result
     */
    public function getOne() {
        $return = array();
        if ($res = $this->execute()) {
            if (pg_num_rows($res) == 1) {
                // make sure only 1 field in query
                if (pg_num_fields($res) == 1) {
                    return pg_fetch_array($res)[0];
                }
                else {
                    $this->triggerError('getOne too many fields');
                    return false;
                }
            }
            else {
                // either 0 or multiple records in result
                if (pg_num_rows($res) == 0) {
                    // no error triggered here. client should trigger if appropriate
                    return false;
                }
                else {
                    $this->triggerError('getOne multiple records found');
                    return false;
                }
            }
        }
        else {
            // query failed. error triggered already in execute
            return false;
        }
    }

    /**
     * @param string $msg
     * sends pertinent query values to error handler
     */
    public function triggerError($msg = 'Query Failure') {
        $errorMsg = "$msg: $this->sql";
        $errorMsg .= "\nArgs: ";
        $errorMsg .= var_export($this->args, true);
        trigger_error($errorMsg);
    }

}
