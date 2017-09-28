<?php

namespace Application\Controller;

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

    public function __construct($entityManager, $userManager)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function registerOrganizerAction()
    {
        $form = $this->getRegisterForm('organizer');

        // Use a different view template for rendering the page.
        $viewModel = new ViewModel();
        $viewModel->setTemplate('user/register/register');
        $viewModel->setVariable('form', $form);
        $viewModel->setVariable('userTypeName', 'Organizzatore');
        $viewModel->setVariable('userTypeCode', 'organizer');
        return $viewModel;
    }

    public function registerCustomerAction()
    {
        $form = $this->getRegisterForm('customer');

        // Use a different view template for rendering the page.
        $viewModel = new ViewModel();
        $viewModel->setTemplate('user/register/register');
        $viewModel->setVariable('form', $form);
        $viewModel->setVariable('userTypeName', 'Cliente');
        $viewModel->setVariable('userTypeCode', 'customer');
        return $viewModel;
    }

    public function loginOrganizerAction()
    {
        $form = $this->getLoginForm('organizer');

        $viewModel = new ViewModel();
        $viewModel->setTemplate('user/login/login');
        $viewModel->setVariable('form', $form);
        $viewModel->setVariable('userTypeName', 'Organizzatore');
        $viewModel->setVariable('userTypeCode', 'organizer');
        return $viewModel;
    }

    public function loginCustomerAction()
    {
        $form = $this->getLoginForm('customer');

        $viewModel = new ViewModel();
        $viewModel->setTemplate('user/login/login');
        $viewModel->setVariable('form', $form);
        $viewModel->setVariable('userTypeName', 'Cliente');
        $viewModel->setVariable('userTypeCode', 'customer');
        return $viewModel;
    }

    public function getLoginForm($userTypeCode)
    {
        $form = new LoginForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();

                $result = $this->userManager->login($userTypeCode, $data['email'], $data['password']);

                if ($result->getCode() === Result::SUCCESS) {

                    return $this->redirect()->toRoute('concert', ['action' => 'concert']);
                } else {
                    foreach ($result->getMessages() as $message) {
                        $this->flashMessenger()->addErrorMessage($message);
                    }
                    $this->redirect()->toRoute('user', ['action' => 'login-' . $userTypeCode]);
                }
            }
        }
        return $form;
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
                return $this->redirect()->toRoute('home');
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
}
