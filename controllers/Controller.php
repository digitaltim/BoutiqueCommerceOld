<?php

namespace It_All\BoutiqueCommerce\Controllers;

use Slim\Container;

abstract class Controller
{
    protected $dic; // dependency injection container
    protected $db;
    protected $view;
    protected $mailer;

    public function __construct(Container $dic)
    {
        //sfd
        //throw new \Exception('test');
        $this->dic = $dic;
        $this->db = $dic['db'];
        $this->dbConnect();
        $this->view = $dic['view'];
        $this->mailer = $dic['mailer'];
        $this->mailer->send('we are in the controller', 'wahoo', ['greg@it-all.com']);
    }

    public function dbConnect()
    {
        $dbSettings = $this->dic['settings']['db'];
        $this->db->connect($dbSettings['database'], $dbSettings['username'], $dbSettings['password']);
    }

}