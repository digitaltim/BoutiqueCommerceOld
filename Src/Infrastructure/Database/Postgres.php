<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure\Database;

use It_All\BoutiqueCommerce\Src\Infrastructure\Database\Queries\QueryBuilder;

/**
 * Class Postgres
 * @package It_All\BoutiqueCommerce\ServicePg
 * A class for connecting to a postgresql database and a few useful meta-query methods
 */
Class Postgres
{
    private $pgConn;

    /** host and password may not be necessary depending on hba.conf */
    public function __construct(
        string $dbname,
        string $user,
        string $password = NULL,
        string $host = NULL,
        int $port = NULL
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

    /**
     * select all tables in a schema
     * @param string $schema
     * @return recordset
     */
    public function getSchemaTables(string $schema = 'public', array $skipTables = [])
    {
        $query = "SELECT table_name FROM information_schema.tables WHERE table_schema = $1";
        foreach ($skipTables as $sk) {
            $query .= " AND table_name";
            $query .= (substr($sk, strlen($sk) - 1) === '%') ? " NOT LIKE '$sk'" : " != '$sk'";
        }
        $query .= " ORDER BY table_name";
        $q = new QueryBuilder($query, $schema);

        return $q->execute();
    }

    /**
     * determines if db table exists
     * @param optional string $tableName
     * @param string $schema
     * @return bool
     */
    public function doesTableExist(string $tableName, string $schema = 'public'): bool
    {
        $q = new QueryBuilder("SELECT table_name FROM information_schema.tables WHERE table_name = $1 AND table_type = 'BASE TABLE' AND table_schema = $2", $tableName, $schema);

        if (pg_num_rows($q->execute()) == 0) {
            return false;
        }
        return true;
    }

    /** note: NOT enough info given by pg_meta_data($tableName); */
    public static function getTableMetaData(string $tableName)
    {
        $q = new QueryBuilder("SELECT column_name, data_type, column_default, is_nullable, character_maximum_length, numeric_precision, udt_name FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = $1", $tableName);

        $rs = $q->execute();
        if (pg_num_rows($rs) == 0) {
            return false;
        }

        return $rs;
    }
}
