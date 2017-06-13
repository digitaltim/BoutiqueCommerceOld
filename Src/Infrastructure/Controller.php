<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure;

use Slim\Container;

abstract class Controller
{
    protected $container; // dependency injection container
    protected $model;
    protected $view;
    protected $routePrefix;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __get($name)
    {
        return $this->container->{$name};
    }

    public function postInsert($request, $response, $args)
    {
        $this->setFormInput($request, $this->model);

        if (!$this->insert()) {
            // redisplay form with errors and input values
            return ($this->view->getInsert($request, $response, $args));
        } else {
            return $response->withRedirect($this->router->pathFor($this->routePrefix.'.index'));
        }
    }

    public function putUpdate($request, $response, $args)
    {
        $this->setFormInput($request, $this->model);

        if (!$updateResponse = $this->update($response, $args)) {
            // redisplay form with errors and input values
            return $this->view->getUpdate($request, $response, $args);
        } else {
            return $updateResponse;
        }
    }

    public function getDelete($request, $response, $args)
    {
        return $this->delete($response, $args);
    }

    protected function setFormInput($request, Model $model, $dbAction = 'insert')
    {
        foreach ($model->getFormFields($dbAction) as $fieldName => $fieldInfo) {
            $_SESSION['formInput'][$fieldName] = ($request->getParam($fieldName) !== null) ? trim($request->getParam($fieldName)) : '';
        }
    }

    protected function update($response, $args, array $changedCheckSkipColumns = null)
    {
        if (!$this->authorization->checkFunctionality($this->routePrefix.'.update')) {
            throw new \Exception('No permission.');
        }

        $primaryKey = $args['primaryKey'];
        $redirectRoute = $this->routePrefix.'.index';

        // make sure there is a record for the primary key in the model
        if (!$record = $this->model->selectForPrimaryKey($primaryKey)) {
            $_SESSION['adminNotice'] = [
                "Record $primaryKey Not Found",
                'adminNoticeFailure'
            ];
            return $response->withRedirect($this->router->pathFor($redirectRoute));
        }

        if (!$this->validator->validate($_SESSION['formInput'], $this->model->getValidationRules('update'))) {
            return false;
        }

        // if no changes made, redirect
        if (!$this->model->hasRecordChanged(
                $_SESSION['formInput'],
                $primaryKey,
                $changedCheckSkipColumns,
                $record
        )) {
            $_SESSION['adminNotice'] = ["No changes made", 'adminNoticeFailure'];
            return $response->withRedirect($this->router->pathFor($redirectRoute));
        }

        // attempt to update the model
        if ($this->model->updateByPrimaryKey($_SESSION['formInput'], $primaryKey)) {
            unset($_SESSION['formInput']);
            $message = 'Updated record '.$primaryKey;
            $this->logger->addInfo($message . ' in '. $this->model->getTableName());
            $_SESSION['adminNotice'] = [$message, 'adminNoticeSuccess'];

            return $response->withRedirect($this->router->pathFor($redirectRoute));

        } else {
            $_SESSION['generalFormError'] = 'Query Failure';
            return false;
        }
    }

    protected function insert(bool $sendEmail = false)
    {
        if (!$this->authorization->checkFunctionality($this->routePrefix.'.insert')) {
            throw new \Exception('No permission.');
        }

        if (!$this->validator->validate($_SESSION['formInput'], $this->model->getValidationRules())) {
            return false;
        }

        // attempt insert
        if ($res = $this->model->insert($_SESSION['formInput'])) {
            unset($_SESSION['formInput']);
            $returned = pg_fetch_all($res);
            $message = 'Inserted record '.$returned[0][$this->model->getPrimaryKeyColumnName()].
                ' into '.$this->model->getTableName();
            $this->logger->addInfo($message);
            if ($sendEmail) {
                $settings = $this->container->get('settings');
                $this->mailer->send(
                    $_SERVER['SERVER_NAME'] . " Event",
                    "Inserted into ".$this->model->getTableName()."\n See event log for details.",
                    [$settings['emails']['owner']]
                );
            }
            $_SESSION['adminNotice'] = [$message, 'adminNoticeSuccess'];

            return true;

        } else {
            $_SESSION['generalFormError'] = 'Query Failure';
            return false;
        }
    }

    protected function delete($response, $args, string $returnColumn = null, bool $sendEmail = false)
    {
        if (!$this->authorization->checkFunctionality($this->routePrefix.'.delete')) {
            throw new \Exception('No permission.');
        }

        $primaryKey = $args['primaryKey'];
        $redirectRoute = $this->routePrefix.'.index';

        if ($res = $this->model->deleteByPrimaryKey($primaryKey, $returnColumn)) {
            $message = 'Deleted record '.$primaryKey;
            if ($returnColumn != null) {
                $returned = pg_fetch_all($res);
                $message .= ' '.$returnColumn.' '.$returned[0][$returnColumn];
            }
            $this->logger->addInfo($message . " from ".$this->model->getTableName()."");
            if ($sendEmail) {
                $settings = $this->container->get('settings');
                $this->mailer->send(
                    $_SERVER['SERVER_NAME'] . " Event",
                    "Deleted record from ".$this->model->getTableName().".\nSee event log for details.",
                    [$settings['emails']['owner']]
                );
            }
            $_SESSION['adminNotice'] = [$message, 'adminNoticeSuccess'];

            return $response->withRedirect($this->router->pathFor($redirectRoute));

        } else {

            $this->logger->addWarning("primary key $primaryKey for ".$this->model->getTableName()." not found for deletion. IP: " . $_SERVER['REMOTE_ADDR']);

            $settings = $this->container->get('settings');
            $this->mailer->send($_SERVER['SERVER_NAME'] . " Event", "primary key $primaryKey not found for deletion. Check event log for details.", [$settings['emails']['programmer']]);

            $_SESSION['adminNotice'] = [$primaryKey.' not found', 'adminNoticeFailure'];

            return $response->withRedirect($this->router->pathFor($redirectRoute));
        }
    }
}
