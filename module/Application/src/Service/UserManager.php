<?php

namespace Application\Service;

use Application\Entity\Customer;
use Application\Entity\Organizer;
use Zend\Authentication\Result;

class UserManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * @var \Zend\Session\SessionManager
     */
    private $sessionManager;

    public function __construct($entityManager, $sessionManager)
    {
        $this->entityManager = $entityManager;
        $this->sessionManager = $sessionManager;
    }

    public function getUser()
    {
        $a = $this->sessionManager->getStorage()->toArray();
        return null;
    }

    public function login($userTypeCode, $email, $password)
    {
        $this->logout();

        $user = null;
        switch ($userTypeCode) {

            case 'customer':
                $user = $this->entityManager->getRepository(Customer::class)
                    ->findOneByEmail($email);
                break;
            case 'organizer':
                $user = $this->entityManager->getRepository(Organizer::class)
                    ->findOneByEmail($email);
                break;
        }

        if (!$user) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND, null, ['Invalid Email.']);
        }

        if ($user->getPassword() != md5($password)) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND, null, ['Invalid credentials.']);
        }

        $this->sessionManager->regenerateId();
        $this->sessionManager->setId($user->getId());
        $this->sessionManager->getStorage()->setMetadata('a','b');
        $this->sessionManager->rememberMe(60 * 60 * 24 * 30);
        return new Result(Result::SUCCESS, $user);
    }

    public function logout()
    {
        $this->sessionManager->forgetMe();
    }

    private function validatePassword($user, $password)
    {
//        $bcrypt = new Bcrypt();
//        $passwordHash = $user->getPassword();
//        if ($bcrypt->verify($password, $passwordHash)) {
        return true;
//        }
//        return false;
    }

    /**
     * @param $userTypeCode
     * @param $user
     * @param $data
     * @return Customer|Organizer
     */
    public function editUser($userTypeCode, $user, $data)
    {
        $isNew = false;
        if ($user === null) {
            if ($userTypeCode == 'customer') {
                $user = new Customer();
            } elseif ($userTypeCode == 'organizer') {
                $user = new Organizer();
            }

            $isNew = true;
        }

        $user->setEmail($data['email']);
        $user->setFullName($data['fullName']);

        if ($data['password']) {
            $user->setPassword(md5($data['password']));
        }

        if ($isNew) {
            $this->entityManager->persist($user);
        }
        $this->entityManager->flush();

        return $user;
    }
}
