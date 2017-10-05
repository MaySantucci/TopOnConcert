<?php

namespace Application\Controller;

use Application\Service\AuthenticationService;
use Application\Entity\Customer;
use Application\Entity\Organizer;
use Application\Form\User\LoginForm;
use Application\Form\User\RegisterForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Result;
use Zend\Uri\Uri;

class UserController extends AbstractActionController
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \Application\Service\UserManager
     */
    private $userManager;

    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    public function __construct($entityManager, $userManager, $authenticationService)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        $this->authenticationService = $authenticationService;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function registerOrganizerAction()
    {
        $this->checkLogin();
        $form = $this->getRegisterForm(Organizer::USER_TYPE);

        // Use a different view template for rendering the page.
        $viewModel = new ViewModel();
        $viewModel->setTemplate('user/register/register');
        $viewModel->setVariable('form', $form);
        $viewModel->setVariable('userTypeName', 'organizzatore');
        $viewModel->setVariable('userTypeCode', Organizer::USER_TYPE);
        return $viewModel;
    }

    public function logoutAction()
    {
        $this->userManager->logout();

        $this->redirect()->toRoute('home');
    }

    public function registerCustomerAction()
    {
        $this->checkLogin();
        $form = $this->getRegisterForm(Customer::USER_TYPE);

        // Use a different view template for rendering the page.
        $viewModel = new ViewModel();
        $viewModel->setTemplate('user/register/register');
        $viewModel->setVariable('form', $form);
        $viewModel->setVariable('userTypeName', 'cliente');
        $viewModel->setVariable('userTypeCode', Customer::USER_TYPE);
        return $viewModel;
    }

    public function loginOrganizerAction()
    {
        $this->checkLogin();
        $form = $this->getLoginForm(Organizer::USER_TYPE);

        if ($this->getRequest()->isPost()) {
            $this->tryLogin($form, Organizer::USER_TYPE);
        }

        $viewModel = new ViewModel();
        $viewModel->setTemplate('user/login/login');
        $viewModel->setVariable('form', $form);
        $viewModel->setVariable('userTypeName', 'organizzatore');
        $viewModel->setVariable('userTypeCode', Organizer::USER_TYPE);
        return $viewModel;
    }

    public function loginCustomerAction()
    {
        $this->checkLogin();
        $form = $this->getLoginForm(Customer::USER_TYPE);

        if ($this->getRequest()->isPost()) {
            $this->tryLogin($form, Customer::USER_TYPE);
        }

        $viewModel = new ViewModel();
        $viewModel->setTemplate('user/login/login');
        $viewModel->setVariable('form', $form);
        $viewModel->setVariable('userTypeName', 'cliente');
        $viewModel->setVariable('userTypeCode', Customer::USER_TYPE);
        return $viewModel;
    }

    public function getLoginForm($userTypeCode)
    {
        $form = new LoginForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);
        }
        return $form;
    }

    public function tryLogin($form, $userTypeCode) {
        if ($form->isValid()) {
            $data = $form->getData();

            $result = $this->userManager->login($userTypeCode, $data['email'], $data['password']);

            if ($result->getCode() === Result::SUCCESS) {
                $this->redirect()->toRoute('home');
            } else {
                foreach ($result->getMessages() as $message) {
                    $this->flashMessenger()->addErrorMessage($message);
                }
                $this->redirect()->toRoute('user', ['action' => 'login-' . $userTypeCode]);
            }
        }
    }


    private function getRegisterForm($userTypeCode)
    {

        $id = (int)$this->params()->fromRoute('id', false);

        if ($id) {
            $user = null;
            if (is_a($this->options['user'], Customer::class)) {
                $user = $this->entityManager->getRepository(Customer::class)->find($id);
            } elseif (is_a($this->options['user'], Organizer::class)) {
                $user = $this->entityManager->getRepository(Organizer::class)->find($id);
            }

            if (!$user) {
                $this->flashMessenger()->addInfoMessage("Dato inesistente del database.");
                $this->redirect()->toRoute('home');
            }

            $form = new RegisterForm('update', $this->entityManager, $user);
        } else {
            $user = null;
            $form = new RegisterForm('create', $this->entityManager);
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();

                $user = $this->userManager->editUser($userTypeCode, $user, $data);

                if ($id) {
                    $this->flashMessenger()->addInfoMessage("Utente modificato con successo.");
                } else {
                    $this->flashMessenger()->addInfoMessage("Utente creato con successo.");
                }

                return $this->redirect()->toRoute('user', ['action' => 'login-' . $userTypeCode, 'id' => $user->getId()]);
            }
        }
        return $form;
    }

    private function checkLogin() {
        if($this->authenticationService->hasIdentity()){
            $this->redirect()->toRoute('home');
        }
    }


}
