<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Admin\Marketing\AdCodes;

use It_All\BoutiqueCommerce\Src\Infrastructure\Controller;

class AdCodesController extends Controller
{
    function putUpdate($request, $response, $args)
    {
        if (!$this->authorization->checkFunctionality('adCodes.update')) {
            throw new \Exception('No permission.');
        }

        $_SESSION['formInput'] = $request->getParsedBody();
        $adCodesModel = new AdCodesModel();

        $error = false;

        if (!$this->validator->validate(
            $request->getParsedBody(),
            $adCodesModel->getValidationRules('update'))
        ) {
            $error = true;
        }

        if (!$error) {
            $id = intval($args['primaryKey']);
            $description = $request->getParam('description');

            // check for no changes made, if so, redirect to list with red notice
            if (!$adCodesModel->hasRecordChanged($request->getParsedBody(), $id)) {
                $_SESSION['adminNotice'] = [
                    "No changes made to $description's testimonial",
                    'adminNoticeFailure'
                ];
                return $response->withRedirect($this->router->pathFor('adCodes.index'));
            }

            // attempt to update the model
            if ($adCodesModel->updateByPrimaryKey($request->getParsedBody(), $id)) {
                unset($_SESSION['formInput']);
                $message = $description . '\'s testimonial updated';
                $this->logger->addInfo($message);
                $_SESSION['adminNotice'] = [$message, 'adminNoticeSuccess'];

                return $response->withRedirect($this->router->pathFor('adCodes.index'));
            } else {
                $_SESSION['generalFormError'] = 'Query Failure';
                $error = true;
            }
        }

        if ($error) {
            // redisplay form with errors and input values
            return (new AdCodesView($this->container))->getUpdate($request, $response, $args);
        }
    }

    function postInsert($request, $response, $args)
    {
        if (!$this->authorization->checkFunctionality('adCodes.insert')) {
            throw new \Exception('No permission.');
        }

        $_SESSION['formInput'] = $request->getParsedBody();
        $adCodesModel = new AdCodesModel();

        $error = false;

        if (!$this->validator->validate(
            $request->getParsedBody(),
            $adCodesModel->getValidationRules())
        ) {
            $error = true;
        }

        if (!$error) {
            // attempt insert
            if ($adCodesModel->insert($request->getParsedBody())) {
                unset($_SESSION['formInput']);
                $message = $request->getParam('description') . '\'s testimonial inserted';
                $settings = $this->container->get('settings');
                $this->mailer->send($_SERVER['SERVER_NAME'] . " Event", $message, [$settings['emails']['owner']]);
                $this->logger->addInfo($message);
                $_SESSION['adminNotice'] = [$message, 'adminNoticeSuccess'];

                return $response->withRedirect($this->router->pathFor('adCodes.index'));
            } else {
                $_SESSION['generalFormError'] = 'Query Failure';
                $error = true;
            }
        }

        if ($error) {
            // redisplay the form with input values and error(s)
            return (new AdCodesView($this->container))->getInsert($request, $response, $args);
        }
    }

    function getDelete($request, $response, $args)
    {
        if (!$this->authorization->checkFunctionality('adCodes.delete')) {
            throw new \Exception('No permission.');
        }

        $adCodesModel = new AdCodesModel();

        if ($res = $adCodesModel->deleteByPrimaryKey(intval($args['primaryKey']), 'id', 'description, results')) {
            $returned = pg_fetch_all($res);
            $message = 'Testimonial by '.$returned[0]['description'].' from '.$returned[0]['results'].' deleted';
            $this->logger->addInfo($message);
            $settings = $this->container->get('settings');
            $this->mailer->send($_SERVER['SERVER_NAME'] . " Event", $message, [$settings['emails']['owner']]);
            $_SESSION['adminNotice'] = [$message, 'adminNoticeSuccess'];

            return $response->withRedirect($this->router->pathFor('adCodes.index'));

        } else {

            $this->logger->addWarning("adCodes.id: " . $args['primaryKey'] . " not found for deletion. IP: " . $_SERVER['REMOTE_ADDR']);

            $settings = $this->container->get('settings');
            $this->mailer->send($_SERVER['SERVER_NAME'] . " Event", "adCodes id not found for deletion. Check events log for details.", [$settings['emails']['programmer']]);

            $_SESSION['adminNotice'] = ['Testimonial not found', 'adminNoticeFailure'];

            return $response->withRedirect($this->router->pathFor('adCodes.index'));
        }
    }
}
