<?php

namespace It_All\BoutiqueCommerce\Controllers;

//use Slim\Views\Twig as View;
use \Slim\Views\PhpRenderer as View;
use It_All\ServicePg\Postgres as Postgres;

abstract class Controller
{
    protected $db;
    protected $view;

    public function __construct(Postgres $db, View $view)
    {
        $this->db = $db;
        $this->view = $view;
    }

}