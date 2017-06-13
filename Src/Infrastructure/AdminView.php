<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure;

use It_All\BoutiqueCommerce\Src\Domain\NavAdmin;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;
use Slim\Container;

class AdminView
{
    protected $container; // dependency injection container
    protected $navigationItems;
    protected $routePrefix;
    protected $model;

    public function __construct(Container $container)
    {
        $this->container = $container;

        // Instantiate navigation navbar contents
        $navAdmin = new NavAdmin($container);
        $this->navigationItems = $navAdmin->getSectionsForUser($container->authorization);
    }

    public function __get($name)
    {
        return $this->container->{$name};
    }

    public function index($request, $response, $args)
    {
        $this->indexView($response, $this->model, $this->routePrefix);
    }

    public function getInsert($request, $response, $args)
    {
        return $this->insertView($response, $this->model, $this->routePrefix);
    }

    public function getUpdate($request, $response, $args)
    {
        return $this->updateView($request, $response, $args, $this->model, $this->routePrefix);
    }

    protected function indexView($response, Model $model, string $routePrefix, string $columns = '*')
    {
        $res = $model->select($columns);

        $insertLink = ($this->authorization->check($this->container->settings['authorization'][$routePrefix.'.insert'])) ? ['text' => 'Insert '.$model->getFormalTableName(false), 'route' => $routePrefix.'.insert'] : false;

        return $this->view->render(
            $response,
            'admin/list.twig',
            [
                'title' => $model->getFormalTableName(),
                'primaryKeyColumn' => $model->getPrimaryKeyColumnName(),
                'insertLink' => $insertLink,
                'updatePermitted' => $this->authorization
                    ->check($this->container->settings['authorization'][$routePrefix.'.update']),
                'updateRoute' => $routePrefix.'.put.update',
                'addDeleteColumn' => true,
                'deleteRoute' => $routePrefix.'.delete',
                'table' => pg_fetch_all($res),
                'navigationItems' => $this->navigationItems
            ]
        );
    }

    protected function insertView($response, Model $model, string $routePrefix)
    {
        $fields = $model->getFormFields('insert');

        return $this->view->render(
            $response,
            'admin/form.twig',
            [
                'title' => 'Insert '.$model->getFormalTableName(false),
                'formActionRoute' => $routePrefix.'.post.insert',
                'formFields' => FormHelper::insertValuesErrors($fields),
                'focusField' => FormHelper::getFocusField(),
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems
            ]
        );
    }

    protected function updateView($request, $response, $args, Model $model, string $routePrefix)
    {
        // make sure there is a record for the model
        if (!$record = $model->selectForPrimaryKey($args['primaryKey'])) {
            $_SESSION['adminNotice'] = [
                "Record $primaryKey Not Found",
                'adminNoticeFailure'
            ];
            return $response->withRedirect($this->router->pathFor($routePrefix.'.index'));
        }

        $fields = $model->getFormFields('update');

        /**
         * data to send to FormHelper - either from the model or from prior input. Note that when sending null FormHelper defaults to using $_SESSION['formInput']. It's important to send null, not $_SESSION['formInput'], because FormHelper unsets $_SESSION['formInput'] after using it.
         * note, this works for post/put because controller calls this method directly in case of errors instead of redirecting
         */
        $fieldData = ($request->isGet()) ? $record : null;

        return $this->view->render(
            $response,
            'admin/form.twig',
            [
                'title' => 'Update ' . ucwords($model->getTableName()),
                'formActionRoute' => $routePrefix.'.put.update',
                'primaryKey' => $args['primaryKey'],
                'formFields' => FormHelper::insertValuesErrors($fields, $fieldData),
                'focusField' => FormHelper::getFocusField(),
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems
            ]
        );
    }
}
