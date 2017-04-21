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
        $this->dic = $dic;
        $this->db = $dic['db'];
        $this->view = $dic['view'];
        $this->mailer = $dic['mailer'];
        //$this->mailer->send('we are in the controller', 'wahoo', ['greg@it-all.com']);
    }
}