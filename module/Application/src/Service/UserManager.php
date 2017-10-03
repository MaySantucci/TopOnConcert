<?php

namespace Application\Service;

use Application\Controller\ConcertController;
use Application\Controller\IndexController;
use Application\Controller\UserController;
use Application\Entity\Customer;
use Application\Entity\Organizer;
use Zend\Authentication\Result;
use Zend\Mvc\MvcEvent;

class UserManager {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \Zend\Session\SessionManager
     */
    private $sessionManager;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    public function __construct($entityManager, $sessionManager, $authenticationService) {
        $this->entityManager = $entityManager;
        $this->sessionManager = $sessionManager;
        $this->authenticationService = $authenticationService;
    }

    public function getUser() {
        $a = $this->sessionManager->getStorage()->toArray();
        var_dump($a);
        die();
        return null;
    }

    public function login($userTypeCode, $email, $password) {
        $this->logout();

        $authenticationAdapter = $this->authenticationService->getAdapter();
        $authenticationAdapter->setCredentials($userTypeCode, $email, $password);

        $result = $this->authenticationService->authenticate();

        return $result;
    }

    public function logout() {
        if ($this->authenticationService->hasIdentity()) {
            $this->authenticationService->clearIdentity();
            $this->sessionManager->forgetMe();
        }
    }

    private function validatePassword($user, $password) {
//        $bcrypt = new Bcrypt();
//        $passwordHash = $user->getPassword();
//        if ($bcrypt->verify($password, $passwordHash)) {
        return true;
//        }
//        return false;
    }

    /**
     * @param $userTypeCode
     * @param $user
     * @param $data
     * @return Customer|Organizer
     */
    public function editUser($userTypeCode, $user, $data) {
        $isNew = false;
        if ($user === null) {
            if ($userTypeCode == Customer::USER_TYPE) {
                $user = new Customer();
            } elseif ($userTypeCode == Organizer::USER_TYPE) {
                $user = new Organizer();
            }

            $isNew = true;
        }

        $user->setEmail($data['email']);
        $user->setFullName($data['fullName']);

        if ($data['password']) {
            $user->setPassword(md5($data['password']));
        }

        if ($isNew) {
            $this->entityManager->persist($user);
        }
        $this->entityManager->flush();

        return $user;
    }

    public function initUser(MvcEvent $event) {
        $controller = $event->getTarget();

        $controllerName = $event->getRouteMatch()->getParam('controller', null);

        // Convert dash-style action name to camel-case.
        $actionName = str_replace('-', '', lcfirst(ucwords($event->getRouteMatch()->getParam('action', null), '-')));

        $allowedControllers = [
            ConcertController::class . '\index',
            IndexController::class . '\index',
            UserController::class . '\loginCustomer',
            UserController::class . '\loginOrganizer',
            UserController::class . '\registerCustomer',
            UserController::class . '\registerOrganizer'
        ];

        if (in_array($controllerName . '\\' . $actionName, $allowedControllers)) {
            return true;
        } else if ($this->authenticationService->hasIdentity()) {

            // @todo aggiungere il controllo sul tipo di utente
            if ($this->authenticationService->getIdentity()->getTypeCode() == Customer::USER_TYPE) {
                echo("sono un cliente");
                //@todo cliente loggato - interfaccia con lista concerti, lista acquisti, logout
                $controller->redirect()->toRoute('home');
            } else {
                echo("sono un organizzatore");
                //@todo organizzatore loggato - interfaccia con lista concerti inseriti con form di crud, logout
                $controller->redirect()->toRoute('home');
            }

            return true;
        }

        $controller->redirect()->toRoute('home');
    }

}
