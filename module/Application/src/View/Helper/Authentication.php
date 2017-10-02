<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Authentication extends AbstractHelper
{

    private $authenticationService;

    public function __construct($authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function getUser()
    {
        if ($this->isLoggedIn()) {
            return $this->authenticationService->getIdentity();
        }
        return null;
    }

    public function isLoggedIn()
    {
        return $this->authenticationService->hasIdentity();
    }

}
