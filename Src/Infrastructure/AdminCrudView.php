<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure;

use It_All\BoutiqueCommerce\Src\Infrastructure\Database\DatabaseTableModel;
use It_All\BoutiqueCommerce\Src\Infrastructure\UserInterface\FormHelper;
use Slim\Container;

abstract class AdminCrudView extends AdminView
{
    protected $routePrefix;
    protected $model;

    public function __construct(
        Container $container,
        DatabaseTableModel $model,
        string $routePrefix
    )
    {
        $this->model = $model;
        $this->routePrefix = $routePrefix;
        parent::__construct($container);
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
        // make sure there is a record for the model
        if (!$record = $this->model->selectForPrimaryKey($args['primaryKey'])) {
            $_SESSION['adminNotice'] = [
                "Record ".$args['primaryKey']." Not Found",
                'adminNoticeFailure'
            ];
            return $response->withRedirect($this->router->pathFor($this->routePrefix.'.index'));
        }

        /**
         * data to send to FormHelper - either from the model or from prior input. Note that when sending null FormHelper defaults to using $_SESSION['formInput']. It's important to send null, not $_SESSION['formInput'], because FormHelper unsets $_SESSION['formInput'] after using it.
         * note, this works for post/put because controller calls this method directly in case of errors instead of redirecting
         */
        $fieldData = ($request->isGet()) ? $record : null;

        return $this->updateView($request, $response, $args, $fieldData);
    }

    protected function indexView($response, string $columns = '*')
    {
        $results = pg_fetch_all($this->model->select($columns, 'PRIMARYKEY', false));

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
                'results' => $results,
                'numResults' => count($results),
                'sortColumn' => $this->model->getPrimaryKeyColumnName(),
                'sortByAsc' => false,
                'navigationItems' => $this->navigationItems
            ]
        );
    }

    protected function insertView($response)
    {
        $formFields = $this->model->getFormFields();

        return $this->view->render(
            $response,
            'admin/form.twig',
            [
                'title' => 'Insert '. $this->model->getFormalTableName(false),
                'formActionRoute' => $this->routePrefix.'.post.insert',
                'formFields' => FormHelper::insertValuesErrors($formFields),
                'focusField' => FormHelper::getFocusField(),
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems
            ]
        );
    }

    protected function updateView($request, $response, $args, $fieldData = null)
    {
        $formFields = $this->model->getFormFields('update');

        return $this->view->render(
            $response,
            'admin/form.twig',
            [
                'title' => 'Update ' . $this->model->getFormalTableName(false),
                'formActionRoute' => $this->routePrefix.'.put.update',
                'primaryKey' => $args['primaryKey'],
                'formFields' => FormHelper::insertValuesErrors($formFields, $fieldData),
                'focusField' => FormHelper::getFocusField(),
                'generalFormError' => FormHelper::getGeneralFormError(),
                'navigationItems' => $this->navigationItems
            ]
        );
    }
}
