<?php

namespace Application\Form\User;

use Application\Entity\Customer;
use Application\Entity\Organizer;
use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;

class RegisterForm extends Form
{
    /**
     * Scenario ('create' or 'update')
     * @var string
     */
    private $scenario;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager = null;

    /**
     * @var Organizer|Customer
     */
    private $user = null;

    public function __construct($scenario = 'create', $entityManager = null, $user = null)
    {
        parent::__construct('register-form');

        $this->setAttribute('method', 'post');

        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->user = $user;

        $this->addElements();
        $this->addInputFilter();
    }

    private function addElements()
    {
        $this->add([
            'type' => Element\Text::class,
            'name' => 'fullName',
            'options' => [
                'label' => 'Nome e Cognome',
            ],
            'attributes' => [
                'placeholder' => 'Nome e Cognome',
                'required' => true,
                'autofocus' => true,
                'value' => $this->user ? $this->user->getFullName() : '',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'company',
            'options' => [
                'label' => 'Azienda',
            ],
            'attributes' => [
                'placeholder' => 'Azienda',
                'required' => false,
                'autofocus' => true,
                'value' => $this->user ? $this->user->getCompany() : '',
            ],
        ]);$this->add([
            'type' => Element\Text::class,
            'name' => 'vat',
            'options' => [
                'label' => 'P.IVA',
            ],
            'attributes' => [
                'placeholder' => 'P.IVA',
                'required' => false,
                'autofocus' => true,
                'value' => $this->user ? $this->user->getVat() : '',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'email',
            'options' => [
                'label' => 'E-mail'
            ],
            'attributes' => [
                'placeholder' => 'E-mail',
                'required' => true,
                'value' => $this->user ? $this->user->getEmail() : '',
            ],
        ]);
        $this->add([
            'type' => Element\Password::class,
            'name' => 'password',
            'options' => [
                'label' => 'Password',
            ],
            'attributes' => [
                'required' => $this->user ? false : true,
            ],
        ]);
        $this->add([
            'type' => Element\Password::class,
            'name' => 'password_confirm',
            'options' => [
                'label' => 'Conferma password',
            ],
            'attributes' => [
                'required' => $this->user ? false : true, //se riceve un nuovo utente lo rende obbligatorio altrimenti no
            ],
        ]);
        $this->add([
            'type' => Element\Submit::class,
            'name' => 'register_submit',
            'attributes' => [
                'value' => 'Registrati'
            ],
        ]);
    }

    private function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
        $inputFilter->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 128
                    ],
                ],
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'allow' => \Zend\Validator\Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ],
                ],
                [
                    'name' => \Application\Validator\EmailExistValidator::class,
                    'options' => [
                        'entityManager' => $this->entityManager,
                        'user' => $this->user
                    ],
                ],
            ],
        ]);
        
                $inputFilter->add([
            'name' => 'vat',
            'required' => false,
            'filters' => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 11,
                        'max' => 11
                    ],
                ],
            ],
        ]);

    }
}