<?php

namespace Application\Controller;

use Application\Form\Concert\ConcertForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ConcertController extends AbstractActionController {

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \Application\Service\ConcertManager
     */
    private $concertManager;

    /**
     * @var \Application\Service\UserManager
     */
    private $userManager;

    public function __construct($entityManager, $concertManager, $userManager) {
        $this->entityManager = $entityManager;
        $this->concertManager = $concertManager;
        $this->userManager = $userManager;
    }

    public function indexAction() {
        $concerts = $this->concertManager->getList();

       // var_dump($this->userManager->getUser());

        $viewModel = new ViewModel();
        $viewModel->setTemplate('concert/index');
        $viewModel->setVariable('concerts', $concerts);
        return $viewModel;
    }

    //metodo per mostrare il template per l'aggiunta/modifica del concerto
    public function concertAction() {
        $form = $this->getConcertForm();
        // Use a different view template for rendering the page.
        $viewModel = new ViewModel();
        $viewModel->setTemplate('concert/register/concert');
        $viewModel->setVariable('form', $form);
        return $viewModel;
    }

    private function getConcertForm() {

        $id = (int) $this->params()->fromRoute('id', false);

        if ($id) {
            $concert = $this->entityManager->getRepository(\Application\Entity\Concert::class)->find($id);

            if (!$concert) {
                $this->flashMessenger()->addInfoMessage("Dato inesistente del database.");
                return $this->redirect()->toRoute('home');
            }

            $form = new ConcertForm($this->entityManager, $concert);
        } else {
            $concert = null;
            $form = new ConcertForm($this->entityManager, $concert);
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();

                $concert = $this->concertManager->editConcert($concert, $data);

                if ($id) {
                    $this->flashMessenger()->addInfoMessage("Concerto modificato con successo.");
                } else {
                    $this->flashMessenger()->addInfoMessage("Concerto creato con successo.");
                }

                return $this->redirect()->toRoute('home');
            }
        }
        return $form;
    }

    // metodo per eliminare un concerto 

    public function deleteConcertAction() {
        $id = (int) $this->params()->fromRoute('id', false);
        $delete = $this->concertManager->deleteConcert($id);

        if ($delete) {
            $this->flashMessenger()->addInfoMessage("Concerto eliminato con successo.");
        } else {            
            $this->flashMessenger()->addInfoMessage("Non &egrave; stato possibile eliminare il concerto.");
        }
        
        return $this->redirect()->toRoute('concert');
    }

}
