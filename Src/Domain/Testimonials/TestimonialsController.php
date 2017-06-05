<?php
declare(strict_types=1);

namespace It_All\BoutiqueCommerce\Src\Domain\Testimonials;

use It_All\BoutiqueCommerce\Src\Infrastructure\Controller;

class TestimonialsController extends Controller
{
    function putUpdate($request, $response, $args)
    {
        if (!$this->authorization->checkFunctionality('testimonials.update')) {
            throw new \Exception('No permission.');
        }

        $_SESSION['formInput'] = $request->getParsedBody();
        $testimonialsModel = new TestimonialsModel();

        $error = false;

        if (!$this->validator->validate(
            $request->getParsedBody(),
            $testimonialsModel->getValidationRules('update'))
        ) {
            $error = true;
        }

        if (!$error) {
            $id = intval($args['primaryKey']);
            $text = $request->getParam('text');
            $person = $request->getParam('person');
            $place = $request->getParam('place');
            $status = $request->getParam('status');

            // check for no changes made, if so, redirect to list with red notice
            if (!$testimonialsModel->recordChanged($id, $text, $person, $place, $status)) {
                $_SESSION['adminNotice'] = [
                    "No changes made to $person\'s testimonial",
                    'adminNoticeFailure'
                ];
                return $response->withRedirect($this->router->pathFor('testimonials.index'));
            }

            // attempt to update the model
            $res = $testimonialsModel->update($id, $text, $person, $place, $status);

            if ($res) {
                unset($_SESSION['formInput']);
                $message = $person . '\'s testimonial updated';
                $this->logger->addInfo($message);
                $_SESSION['adminNotice'] = [$message, 'adminNoticeSuccess'];

                return $response->withRedirect($this->router->pathFor('testimonials.index'));
            } else {
                $_SESSION['generalFormError'] = 'Query Failure';
                $error = true;
            }
        }

        if ($error) {
            // redisplay form with errors and input values
            return (new TestimonialsView($this->container))->getUpdate($request, $response, $args);
        }
    }

    function postInsert($request, $response, $args)
    {
        if (!$this->authorization->checkFunctionality('testimonials.insert')) {
            throw new \Exception('No permission.');
        }

        $_SESSION['formInput'] = $request->getParsedBody();
        $testimonialsModel = new TestimonialsModel();

        $error = false;

        if (!$this->validator->validate(
            $request->getParsedBody(),
            $testimonialsModel->getValidationRules())
        ) {
            $error = true;
        }

        if (!$error) {
            // attempt insert
            $person = $request->getParam('person');

            $res = $testimonialsModel->insert(
                $request->getParam('text'),
                $person,
                $request->getParam('place'),
                $request->getParam('status')
            );

            if ($res) {
                unset($_SESSION['formInput']);
                $message = $person . '\'s testimonial inserted';
                $settings = $this->container->get('settings');
                $this->mailer->send($_SERVER['SERVER_NAME'] . " Event", $message, [$settings['emails']['owner']]);
                $this->logger->addInfo($message);
                $_SESSION['adminNotice'] = [$message, 'adminNoticeSuccess'];

                return $response->withRedirect($this->router->pathFor('testimonials.index'));
            } else {
                $_SESSION['generalFormError'] = 'Query Failure';
                $error = true;
            }
        }

        if ($error) {
            // redisplay the form with input values and error(s)
            return (new TestimonialsView($this->container))->getInsert($request, $response, $args);
        }
    }

    function getDelete($request, $response, $args)
    {
        if (!$this->authorization->checkFunctionality('testimonials.delete')) {
            throw new \Exception('No permission.');
        }

        $testimonialsModel = new TestimonialsModel();

        if ($res = $testimonialsModel->delete(intval($args['primaryKey']))) {
            $returned = pg_fetch_all($res);
            $message = 'Testimonial '.$returned[0]['username'].' deleted';
            $this->logger->addInfo($message);
            $settings = $this->container->get('settings');
            $this->mailer->send($_SERVER['SERVER_NAME'] . " Event", $message, [$settings['emails']['owner']]);
            $_SESSION['adminNotice'] = [$message, 'adminNoticeSuccess'];

            return $response->withRedirect($this->router->pathFor('testimonials.index'));

        } else {

            $this->logger->addWarning("testimonials.id: " . $args['primaryKey'] . " not found for deletion. IP: " . $_SERVER['REMOTE_ADDR']);

            $settings = $this->container->get('settings');
            $this->mailer->send($_SERVER['SERVER_NAME'] . " Event", "testimonials id not found for deletion. Check events log for details.", [$settings['emails']['programmer']]);

            $_SESSION['adminNotice'] = ['Testimonial not found', 'adminNoticeFailure'];

            return $response->withRedirect($this->router->pathFor('testimonials.index'));
        }
    }
}
