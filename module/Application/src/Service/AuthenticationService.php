<?php

namespace Application\Service;

use Application\Entity\Customer;
use Application\Entity\Organizer;
use Zend\Authentication\AuthenticationService as ZendAuthenticationService;
use Zend\Authentication\Storage\StorageInterface;
use Zend\Authentication\Adapter\AdapterInterface;

class AuthenticationService extends ZendAuthenticationService {

    /**
     * @return Customer|Organizer|null
     */
    public function getIdentity() {
        return parent::getIdentity();
    }

    public function __construct(StorageInterface $storage, AdapterInterface $adapter) {
        parent::__construct($storage, $adapter);
    }

}
