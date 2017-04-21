<?php
namespace It_All\BoutiqueCommerce\Controllers;

class AdminController extends Controller
{
    public function index($request, $response)
    {
        $q = $this->db->queryBuilderFactory("SELECT * FROM admins");
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