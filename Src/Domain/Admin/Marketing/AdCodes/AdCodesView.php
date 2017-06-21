<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\AdCodes;

use It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\AdCodes\AdCodeModel;

use It_All\BoutiqueCommerce\Src\Infrastructure\AdminCrudView;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;
use Slim\Container;

class AdCodesView extends AdminCrudView
{
    public function __construct(Container $container)
    {
        parent::__construct($container, new AdCodesModel(), 'adCodes');
    }

//    public function index($request, $response, $args)
//    {
//        $adCodes = [];
//        $res = $this->model->select();
//
//        while ($row = pg_fetch_assoc($res)) {
//            $adCodes[] = AdCodeModel::getInstanceFromDatabaseRecord($row);
//        }
//
//        $insertLink = ($this->authorization->check($this->container->settings['authorization'][$this->routePrefix.'.insert'])) ? ['text' => 'Insert '.$this->model->getFormalTableName(false), 'route' => $this->routePrefix.'.insert'] : false;
//
//        return $this->view->render(
//            $response,
//            'admin/listObjects.twig',
//            [
//                'title' => $this->model->getFormalTableName(),
//                'primaryKeyColumn' => $this->model->getPrimaryKeyColumnName(),
//                'insertLink' => $insertLink,
//                'updatePermitted' => $this->authorization
//                    ->check($this->container->settings['authorization'][$this->routePrefix.'.update']),
//                'updateRoute' => $this->routePrefix.'.put.update',
//                'addDeleteColumn' => true,
//                'deleteRoute' => $this->routePrefix.'.delete',
//                'objects' => $adCodes,
//                'navigationItems' => $this->navigationItems
//            ]
//        );
//    }

}
