<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Controllers;

use It_All\BoutiqueCommerce\Models\Admins;
use It_All\BoutiqueCommerce\Models\DbTable;
use Slim\Container;

class CrudController extends Controller
{
    private $tableName;
    private $model;

    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    public function show($request, $response, $args)
    {
        $this->model = new DbTable('admins', $this->db);
        $this->tableName = $args['table'];
        echo 'show '.$this->tableName;
        if ($res = $this->model->select('*')) {
//            $results = (pg_num_rows($res) > 0) ? $UiRsDbTable->makeTable($res) : 'No results';
            while ($row = pg_fetch_assoc($res)) {
                echo $row['name'];
            }
        }
        else {
            echo "Query Error";
        }

    }

    public function __get($name)
    {
        return $this->{$name};
    }
}