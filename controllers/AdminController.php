<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Controllers;

use It_All\BoutiqueCommerce\Utilities\Database;


class AdminController extends Controller
{
    public function index($request, $response)
    {
        $q = new Database\QueryBuilder("SELECT * FROM admins");
        $rs = $q->execute();
        $rows = [];
        while ($row = pg_fetch_assoc($rs)) {
            $rows[] = $row;
        }
        return $this->view->render($response, 'admin.twig', ['title' => 'test title', 'rows' => $rows]);
    }

    function show($request, $response, $args)
    {

    }
}