<?php

namespace Application\Service;

use Application\Entity\Customer;
use Application\Entity\Organizer;

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

    public function login($email, $password, $rememberMe)
    {
//        $this->logout();
//
//        $userAdapter = $this->UserService->getAdapter();
//        $userAdapter->setEmail($email);
//        $userAdapter->setPassword($password);
//
//        $result = $this->UserService->authenticate();
//
//        if ($result->getCode() == Result::SUCCESS && $rememberMe) {
//            $this->sessionManager->rememberMe(60 * 60 * 24 * 30);
//        }
//        return $result;
        return true;
    }

    public function logout()
    {
//        if ($this->UserService->hasIdentity()) {
//            $this->UserService->clearIdentity();
//        }
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
     * @param $user
     * @param $data
     * @return Customer|Organizer
     */
    public function editUser($user, $data)
    {
        $isNew = false;
        if ($user === null) {
            if (is_a($this->options['user'], Customer::class)) {
                $user  = new Customer();
            } elseif (is_a($this->options['user'], Organizer::class)) {
                $user  = new Organizer();
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
