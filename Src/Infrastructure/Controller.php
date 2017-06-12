<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Infrastructure;

use Slim\Container;

abstract class Controller
{
    protected $container; // dependency injection container

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __get($name)
    {
        return $this->container->{$name};
    }

    protected function setFormInput($request, Model $model, $dbAction = 'insert')
    {
        foreach ($model->getFormFields($dbAction) as $fieldName => $fieldInfo) {
            $_SESSION['formInput'][$fieldName] = ($request->getParam($fieldName) !== null) ? trim($request->getParam($fieldName)) : '';
        }
    }

    protected function update($request, $response, $primaryKey, string $route, Model $model, string $redirectRoute, string $primaryKeyName = 'id', array $changedCheckSkipColumns = null)
    {
        if (!$this->authorization->checkFunctionality($route)) {
            throw new \Exception('No permission.');
        }

        // make sure there is a record for the primary key in the model
        if (!$record = $model->selectForPrimaryKey($primaryKey)) {
            $_SESSION['adminNotice'] = [
                "Record $primaryKey Not Found",
                'adminNoticeFailure'
            ];
            return $response->withRedirect($this->router->pathFor($redirectRoute));
        }

        if (!$this->validator->validate($_SESSION['formInput'], $model->getValidationRules('update'))) {
            return false;
        }

        // if no changes made, redirect
        if (!$model->hasRecordChanged(
            $_SESSION['formInput'],
                $primaryKey,
                $primaryKeyName,
                $changedCheckSkipColumns,
                $record
        )) {
            $_SESSION['adminNotice'] = ["No changes made", 'adminNoticeFailure'];
            return $response->withRedirect($this->router->pathFor($redirectRoute));
        }

        // attempt to update the model
        if ($model->updateByPrimaryKey($_SESSION['formInput'], $primaryKey, $primaryKeyName)) {
            unset($_SESSION['formInput']);
            $message = 'Updated record '.$primaryKey;
            $this->logger->addInfo($message . ' in '. $model->getTableName());
            $_SESSION['adminNotice'] = [$message, 'adminNoticeSuccess'];

            return $response->withRedirect($this->router->pathFor($redirectRoute));

        } else {
            $_SESSION['generalFormError'] = 'Query Failure';
            return false;
        }
    }

    protected function insert(string $route, Model $model, string $primaryKeyName = 'id', bool $sendEmail = false)
    {
        if (!$this->authorization->checkFunctionality($route)) {
            throw new \Exception('No permission.');
        }

        if (!$this->validator->validate($_SESSION['formInput'], $model->getValidationRules())) {
            return false;
        }

        // attempt insert
        if ($res = $model->insert($_SESSION['formInput'], $primaryKeyName)) {
            unset($_SESSION['formInput']);
            $returned = pg_fetch_all($res);
            $message = 'Inserted record '.$returned[0][$primaryKeyName].
                ' into '.$model->getTableName();
            $this->logger->addInfo($message);
            if ($sendEmail) {
                $settings = $this->container->get('settings');
                $this->mailer->send(
                    $_SERVER['SERVER_NAME'] . " Event",
                    "Inserted into ".$model->getTableName()."\n See event log for details.",
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

    protected function delete($response, $primaryKey, string $route, Model $model, string $redirectRoute, string $primaryKeyName = 'id', string $returnColumn = null, bool $sendEmail = false)
    {
        if (!$this->authorization->checkFunctionality($route)) {
            throw new \Exception('No permission.');
        }

        if ($res = $model->deleteByPrimaryKey($primaryKey, $primaryKeyName, $returnColumn)) {
            $message = 'Deleted record '.$primaryKey;
            if ($returnColumn != null) {
                $returned = pg_fetch_all($res);
                $message .= ' '.$returnColumn.' '.$returned[0][$returnColumn];
            }
            $this->logger->addInfo($message . " from ".$model->getTableName()."");
            if ($sendEmail) {
                $settings = $this->container->get('settings');
                $this->mailer->send(
                    $_SERVER['SERVER_NAME'] . " Event",
                    "Deleted record from ".$model->getTableName().".\nSee event log for details.",
                    [$settings['emails']['owner']]
                );
            }
            $_SESSION['adminNotice'] = [$message, 'adminNoticeSuccess'];

            return $response->withRedirect($this->router->pathFor($redirectRoute));

        } else {

            $this->logger->addWarning("primary key $primaryKey for ".$model->getTableName()." not found for deletion. IP: " . $_SERVER['REMOTE_ADDR']);

            $settings = $this->container->get('settings');
            $this->mailer->send($_SERVER['SERVER_NAME'] . " Event", "primary key $primaryKey not found for deletion. Check event log for details.", [$settings['emails']['programmer']]);

            $_SESSION['adminNotice'] = [$primaryKey.' not found', 'adminNoticeFailure'];

            return $response->withRedirect($this->router->pathFor($redirectRoute));
        }
    }
}
