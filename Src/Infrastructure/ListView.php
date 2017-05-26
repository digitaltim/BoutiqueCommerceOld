<?php
declare(strict_types=1);

/*
 * Generic view (GUI) contains logic common across any type of list.
 * Does not need a controller since it only gets
 * https://www.sitepoint.com/community/t/mvc-vs-pac/28490/3
 */

namespace It_All\BoutiqueCommerce\Src\Infrastructure;

use Slim\Container;

class ListView
{
    protected $container; // dependency injection container
    // In the example, the model is passed into this view's constructor...
    // How can we instantiate a model in the routes file or better should we?
    // Thus we don't really need this property here in this implementation
    private $linkingModel;

    public function __construct(Container $container)
    {
        $this->container = $container;
        // Instantiate services/dependencies
        $container['db'];
        $container['view'];
        $container['mailer'];
    }

    public function __get($name)
    {
        return $this->container->{$name};
    }

    public function output($request, $response, $args)
    {
        // Instantiate the domain model
        $domainModelName = "It_All\\BoutiqueCommerce\\Src\\Domain\\".ucwords($args['table'])."\\".ucwords($args['table'])."Model";
        $domainModel = new $domainModelName($this->db);
        // Instantiate the model (glue between domain model and view)
        $linkingModelName = "It_All\\BoutiqueCommerce\\Src\\Domain\\".ucwords($args['table'])."\\Every".ucwords($args['table'])."List";
        // var_dump($linkingModelName);
        // die();
        $this->linkingModel = new $linkingModelName($domainModel);
        // Use model to call common interface method "getRecords"
        return $this->view->render($response, 'admin/list.twig', ['title' => $args['table'], 'results' => $this->linkingModel->getRecords()]);
    }
}
