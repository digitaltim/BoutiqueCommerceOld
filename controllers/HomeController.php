<?php
namespace It_All\BoutiqueCommerce\Controllers;

class HomeController extends Controller
{
    public function index($request, $response)
    {
        $q = $this->db->queryBuilderFactory("SELECT * FROM admins");
        $rs = $q->execute();
        $rows = [];
        while ($row = pg_fetch_row($rs)) {
            $rows[] = $row;
        }
        return $this->view->render($response, 'home.php', ['title' => 'test title', 'rows' => $rows]);
    }
}