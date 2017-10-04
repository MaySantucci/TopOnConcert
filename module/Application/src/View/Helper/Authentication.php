<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Authentication extends AbstractHelper
{
    /**
     * @var \Application\Service\UserManager
     */
    private $userManager;

    public function __construct($userManager)
    {
        $this->userManager = $userManager;
    }

    public function getUser()
    {
        return $this->userManager->getCurrentUser();
    }

    public function isLoggedIn()
    {
        return $this->userManager->isLoggedin();
    }

    public function isCustomer()
    {
        return $this->userManager->isCustomer();
    }

    public function isOrganizer()
    {
        return $this->userManager->isOrganizer();
    }

}
