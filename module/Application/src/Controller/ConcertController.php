<?php

namespace Application\Controller;

use Application\Entity\Concert;
use Application\Entity\Ticket;
use Application\Form\Concert\ConcertForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ConcertController extends AbstractActionController
{

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

    public function __construct($entityManager, $concertManager, $userManager)
    {
        $this->entityManager = $entityManager;
        $this->concertManager = $concertManager;
        $this->userManager = $userManager;
    }

    public function indexAction()
    {
        if ($this->userManager->isOrganizer()) {
            $concerts = $this->userManager->getCurrentUser()->getConcerts();
        } else {
            $concerts = $this->concertManager->getList();
        }

        $viewModel = new ViewModel();
        $viewModel->setTemplate('concert/index');
        $viewModel->setVariable('concerts', $concerts);
        return $viewModel;
    }

    //metodo per mostrare il template per l'aggiunta/modifica del concerto
    public function concertAction()
    {
        $form = $this->getConcertForm();
        // Use a different view template for rendering the page.
        $viewModel = new ViewModel();
        $viewModel->setTemplate('concert/register/concert');
        $viewModel->setVariable('form', $form);
        return $viewModel;
    }

    private function getConcertForm()
    {
        $id = (int)$this->params()->fromRoute('id', false);

        if ($id) {
            $concert = $this->entityManager->getRepository(\Application\Entity\Concert::class)->find($id);

            if (!$concert) {
                $this->flashMessenger()->addInfoMessage("Dato inesistente del database.");
                return $this->redirect()->toRoute('home');
            }

            $form = new ConcertForm($this->entityManager, $concert);
            $form->setAttribute('action', $this->url()->fromRoute('concert', ['action' => 'concert', 'id' => $id]));

        } else {
            $concert = null;
            $form = new ConcertForm($this->entityManager, $concert);
            $form->setAttribute('action', $this->url()->fromRoute('concert', ['action' => 'concert']));
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

                return $this->redirect()->toRoute('concert');
            }
        }
        return $form;
    }

    // metodo per eliminare un concerto 

    public function deleteConcertAction()
    {
        $id = (int)$this->params()->fromRoute('id', false);
        $delete = $this->concertManager->deleteConcert($id);

        if ($delete) {
            $this->flashMessenger()->addInfoMessage("Concerto eliminato con successo.");
        } else {
            $this->flashMessenger()->addInfoMessage("Non &egrave; stato possibile eliminare il concerto.");
        }

        return $this->redirect()->toRoute('concert');
    }

    public function buyConcertAction()
    {
        $id = (int)$this->params()->fromRoute('id', false);

        $concert = $this->entityManager->getRepository(Concert::class)->find($id);

        $viewModel = new ViewModel();
        $viewModel->setTemplate('concert/buyconcert');
        $viewModel->setVariable('concert', $concert);
        return $viewModel;

    }

    public function payConcertAction()
    {
        $id = (int)$this->params()->fromRoute('id', false);
        $user = $this->userManager->getCurrentUser();

        /** @var \Application\Entity\Concert $concert */
        $concert = $this->entityManager->getRepository(Concert::class)->find($id);
        if($concert->getAvailability() > 0){
            $concert->setAvailability($concert->getAvailability()-1);
            $ticket = new Ticket();
            $ticket->setConcert($concert);
            $ticket->setCustomer($user);
            $this->entityManager->persist($ticket);
            $this->entityManager->flush();
            return $this->redirect()->toRoute('ticket');

        } else {
            return $this->redirect()->toRoute('concert', ['action'=>'buyConcert', 'id' => $id] );
        }

    }
    
        public function cancelBuyAction() {
        $id_ticket = (int) $this->params()->fromRoute('id', false);
        $ticket = $this->entityManager->getRepository(Ticket::class)->find($id_ticket);
        
        
        /** @var \Application\Entity\Ticket $ticket */
        $id_concert = $ticket->getConcert()->getId();

        /** @var \Application\Entity\Concert $concert */
        $concert = $this->entityManager->getRepository(Concert::class)->find($id_concert);
        $concert->setAvailability($concert->getAvailability() + 1);

        $this->entityManager->remove($ticket);
        $this->entityManager->flush();

        return $this->redirect()->toRoute('ticket');
    }

}
