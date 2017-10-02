<?php

namespace Application\Service;

use Application\Entity\Customer;
use Application\Entity\Organizer;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;

class AuthAdapter implements AdapterInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private $email;
    private $password;
    private $userTypeCode;

    public function setCredentials($userTypeCode, $email, $password)
    {
        $this->email = $email;
        $this->password = $password;
        $this->userTypeCode = $userTypeCode;
    }

    /**
     * Performs an authentication attempt.
     */
    public function authenticate()
    {
        $user = null;
        switch ($this->userTypeCode) {
            case 'customer':
                $user = $this->entityManager->getRepository(Customer::class)
                    ->findOneByEmail($this->email);
                break;
            case 'organizer':
                $user = $this->entityManager->getRepository(Organizer::class)
                    ->findOneByEmail($this->email);
                break;
        }
        if (!$user) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND, null, ['Invalid Email.']);
        }

        if ($user->getPassword() != md5($this->password)) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND, null, ['Invalid credentials.']);
        }

        return new Result(Result::SUCCESS, $user);
    }
}
