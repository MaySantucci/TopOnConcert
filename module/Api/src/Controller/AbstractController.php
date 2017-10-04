<?php

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Mvc\MvcEvent;
use Zend\Json\Decoder;

abstract class AbstractController extends AbstractRestfulController
{

    protected $collectionOptions = [];
    protected $resourceOptions = [];
    protected $othersOptions = [];

    /**
     * @var array
     */
    private $restData;

    /**
     * Restituisce la lista dei Method HTTP consentiti
     *
     * @return array
     */
    private function getOptions()
    {
        $options = $this->othersOptions;
        $options = array_merge($this->resourceOptions, $options);
        $options = array_merge($this->collectionOptions, $options);
        return array_unique($options);
    }

    /**
     * Unisce i dati ricevuti dall'utente tramite Json, Post e Files
     *
     * @return array
     */
    public function getRestData()
    {
        if (null === $this->restData) {
            $params = array();
            if ($this->getRequest()->getContent()) {
                $params = array_merge_recursive($params, Decoder::decode($this->getRequest()->getContent(), 1));
            }
            if ($this->getRequest()->isPost()) {
                $params = array_merge_recursive($params, $this->getRequest()->getPost()->toArray());
            }
            if ($this->getRequest()->getFiles()) {
                $params = array_merge_recursive($params, $this->getRequest()->getFiles()->toArray());
            }
            $this->restData = $params;
        }
        return $this->restData;
    }

    /**
     * Verifica se le credenziali di autenticazione sono corrette prima di
     * gestire il relativo controller
     *
     * @param MvcEvent $event
     * @return mixed
     */
    public function onDispatch(MvcEvent $event)
    {
        $response = $event->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Access-Control-Allow-Origin', '*');
        $headers->addHeaderLine('Access-Control-Allow-Headers', 'x-requested-with, Content-Type, Origin, Authorization, Accept, client-security-token');
        $headers->addHeaderLine('Access-Control-Allow-Methods', implode(',', $this->getOptions()));

        if ($event->getRequest()->getMethod() == 'OPTIONS') {
            return $response;
        }

        parent::onDispatch($event);
    }

}
