<?php

namespace KikCMS\Plugins;

use KikCMS\Classes\Translator;
use KikCMS\Services\UserService;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;

/**
 * @property UserService $userService
 * @property Translator $translator
 */
class SecurityPlugin extends Plugin
{
    /**
     * This action is executed before execute any action in the application
     *
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return bool
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $controller         = $dispatcher->getControllerName();
        $isLoggedIn         = $this->userService->isLoggedIn();
        $allowedControllers = ['login', 'deploy', 'errors'];

        if (!$isLoggedIn && !in_array($controller, $allowedControllers)) {
            if($this->request->isAjax()){
                $this->response->setStatusCode(440, 'Session expired');
            } else {
                $this->flash->notice($this->translator->tl('login.expired'));
                $this->response->redirect('cms/login');
            }

            return false;
        }

        if($isLoggedIn && $controller == 'login'){
            $this->response->redirect('cms');
            return false;
        }

        return true;
    }
}
