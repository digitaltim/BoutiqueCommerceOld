<?php
declare(strict_types=1);

namespace It_All\ServicePg;

Class Postgres
{
    private $pgConn;

    /** host and password may not be necessary depending on hba.conf */
    public function __construct(
        string $dbname, 
        string $user, 
        string $password = NULL, 
        string $host = NULL,
        string $port = NULL
    )
    {
        $connectionString = "dbname=$dbname user=$user";
        if (!($host === NULL)) {
            $connectionString .= " host=$host";
        }
        if (!($password === NULL)) {
            $connectionString .= " password=$password";
        }
        if (!($port === NULL)) {
            $connectionString .= " port=$port";
        }
        $connectionString .= " connect_timeout=5";
        
        if (!$this->pgConn = pg_connect($connectionString)) {
            throw new \Exception('postgres connection failure');
        }
    }

    public function getPgConn() {
        return $this->pgConn;
    }

//    public function queryBuilderFactory()
//    {
//        $args = func_get_args();
//        var_dump($args);
//        if ($args > 0) {
//            $sql = $args[0];
//            array_shift($args); // drop the first one
//        } else {
//            $sql = null;
//        }
//        return new QueryBuilder($sql, $args);
//        // note func_num_args returns 0 if just 1 argument of null passed in
//        if (count($args) > 0) {
//            call_user_func_array(array($this, 'add'), $args);
//        }
////        return ($sql === null) ? new QueryBuilder() : new QueryBuilder($sql);
//    }

    public function insertBuilderFactory(string $dbTable)
    {
        return new InsertBuilder($this->pgConn, $dbTable);
    }

    public function updateBuilderFactory(string $dbTable, string $updateOnColumnName, string $updateOnColumnValue)
    {
        return new UpdateBuilder($this->pgConn, $dbTable, $updateOnColumnName, $updateOnColumnValue);
    }

    public function deleteByPrimaryKey($dbTable, $pkValue, $pkName = 'id')
    {
        $q = $this->queryBuilderFactory("DELETE FROM $dbTable WHERE $pkName=$1", $pkValue);
        $res = $q->execute();
        return (pg_affected_rows($res) == 0) ? false : true;
    }

    /**
     * select all tables in a schema
     * @param string $schema
     * @return recordset
     */
    public function getSchemaTables($schema = 'public', $skipTables = [])
    {
        $query = "SELECT table_name FROM information_schema.tables WHERE table_schema = $1";
        foreach ($skipTables as $sk) {
            $query .= " AND table_name";
            $query .= (substr($sk, strlen($sk) - 1) === '%') ? " NOT LIKE '$sk'" : " != '$sk'";
        }
        $query .= " ORDER BY table_name";
        // todo check out schema arg
//        $q = new QueryBuilder($query, $schema);
        $q = $this->queryBuilderFactory($query, $schema);

        return $q->execute();
    }

    /**
     * determines if db table exists
     * @param optional string $tableName
     * @param string $schema
     * @return bool
     */
    public function doesTableExist($tableName, $schema = 'public')
    {
        $q = $this->queryBuilderFactory("SELECT table_name FROM information_schema.tables WHERE table_name = $1 AND table_type = 'BASE TABLE' AND table_schema = $2", $tableName, $schema);

        if (pg_num_rows($q->execute()) == 0) {
            return false;
        }
        return true;
    }

    /**
     * @param string $tableName
     * @return recordset
     */
    public function getTableMetaData(string $tableName)
    {
        $q = new QueryBuilder("SELECT column_name, data_type, column_default, is_nullable, character_maximum_length, numeric_precision, udt_name FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = $1", $tableName);

        return $q->execute();
        // note: NOT enough info given by $dbTableFields = pg_meta_data($this->pgConn, $tableName);
    }

}