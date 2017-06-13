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
        $this->indexView($response);
    }

    public function getInsert($request, $response, $args)
    {
        return $this->insertView($response);
    }

    public function getUpdate($request, $response, $args)
    {
        return $this->updateView($request, $response, $args);
    }

    protected function indexView($response, string $columns = '*')
    {
        $res = $this->model->select($columns);

        $insertLink = ($this->authorization->check($this->container->settings['authorization'][$this->routePrefix.'.insert'])) ? ['text' => 'Insert '.$this->model->getFormalTableName(false), 'route' => $this->routePrefix.'.insert'] : false;

        return $this->view->render(
            $response,
            'admin/list.twig',
            [
                'title' => $this->model->getFormalTableName(),
                'primaryKeyColumn' => $this->model->getPrimaryKeyColumnName(),
                'insertLink' => $insertLink,
                'updatePermitted' => $this->authorization
                    ->check($this->container->settings['authorization'][$this->routePrefix.'.update']),
                'updateRoute' => $this->routePrefix.'.put.update',
                'addDeleteColumn' => true,
                'deleteRoute' => $this->routePrefix.'.delete',
                'table' => pg_fetch_all($res),
                'navigationItems' => $this->navigationItems
            ]
        );
    }

    protected function insertView($response)
    {
        $fields = $this->model->getFormFields('insert');

        return $this->view->render(
            $response,
            'admin/form.twig',
            [
                'title' => 'Insert '.$this->model->getFormalTableName(false),
                'formActionRoute' => $this->routePrefix.'.post.insert',
                'formFields' => FormHelper::insertValuesErrors($fields),
                'focusField' => FormHelper::getFocusField(),
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems
            ]
        );
    }

    protected function updateView($request, $response, $args)
    {
        // make sure there is a record for the model
        if (!$record = $this->model->selectForPrimaryKey($args['primaryKey'])) {
            $_SESSION['adminNotice'] = [
                "Record ".$args['primaryKey']." Not Found",
                'adminNoticeFailure'
            ];
            return $response->withRedirect($this->router->pathFor($this->routePrefix.'.index'));
        }

        $fields = $this->model->getFormFields('update');

        /**
         * data to send to FormHelper - either from the model or from prior input. Note that when sending null FormHelper defaults to using $_SESSION['formInput']. It's important to send null, not $_SESSION['formInput'], because FormHelper unsets $_SESSION['formInput'] after using it.
         * note, this works for post/put because controller calls this method directly in case of errors instead of redirecting
         */
        $fieldData = ($request->isGet()) ? $record : null;

        return $this->view->render(
            $response,
            'admin/form.twig',
            [
                'title' => 'Update ' . ucwords($this->model->getTableName()),
                'formActionRoute' => $this->routePrefix.'.put.update',
                'primaryKey' => $args['primaryKey'],
                'formFields' => FormHelper::insertValuesErrors($fields, $fieldData),
                'focusField' => FormHelper::getFocusField(),
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems
            ]
        );
    }
}
