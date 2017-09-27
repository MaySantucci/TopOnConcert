<?php

namespace Application\Controller;

use Application\Form\User\RegisterForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function __construct($entityManager, $userManager) {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function registerOrganizerAction()
    {
        $form = $this->getForm();

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
        $form = $this->getForm();

        // Use a different view template for rendering the page.
        $viewModel = new ViewModel();
        $viewModel->setTemplate('user/register/register');
        $viewModel->setVariable('form', $form);
        $viewModel->setVariable('userTypeName', 'Cliente');
        $viewModel->setVariable('userTypeCode', 'customer');
        return $viewModel;
    }

    public function getForm() {

        $id = (int) $this->params()->fromRoute('id', false);

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

                $user = $this->userManager->editUser($user, $data);

                if ($id) {
                    $this->flashMessenger()->addInfoMessage("Utente modificato con successo.");
                } else {
                    $this->flashMessenger()->addInfoMessage("Utente creato con successo.");
                }

                return $this->redirect()->toRoute('user', ['action' => 'edit', 'id' => $user->getId()]);
            }
        }
        return $form;
    }
}
