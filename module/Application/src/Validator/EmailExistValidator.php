<?php

namespace Application\Validator;

use Zend\Validator\AbstractValidator;
use Application\Entity\Customer;
use Application\Entity\Organizer;

class EmailExistValidator extends AbstractValidator
{

    const NOT_SCALAR = 'notScalar';
    const USER_EXISTS = 'alreadyExist';

    /**
     * @var array
     */
    protected $options = array(
        'entityManager' => null,
        'user' => null
    );

    /**
     * @var array
     */
    protected $messageTemplates = [
        self::NOT_SCALAR => "The email must be a scalar value",
        self::USER_EXISTS => "The same email already exists"
    ];

    public function __construct($options = null)
    {
        if (is_array($options)) {
            if (isset($options['entityManager'])) {
                $this->options['entityManager'] = $options['entityManager'];
            }
            if (isset($options['user'])) {
                $this->options['user'] = $options['user'];
            }
        }
        parent::__construct($options);
    }

    public function isValid($value)
    {
        if (!is_scalar($value)) {
            $this->error(self::NOT_SCALAR);
            return false;
        }

        $entityManager = $this->options['entityManager'];

        $loadedUser = null;
        if (is_a($this->options['user'], Customer::class)) {
            $loadedUser = $entityManager->getRepository(Customer::class)->findOneByEmail($value);
        } elseif (is_a($this->options['user'], Organizer::class)) {
            $loadedUser = $entityManager->getRepository(Organizer::class)->findOneByEmail($value);
        }

        if ($this->options['user'] == null) {
            $isValid = ($loadedUser == null);
        } else {
            if ($this->options['user']->getEmail() != $value && $loadedUser != null) {
                $isValid = false;
            } else {
                $isValid = true;
            }
        }

        if (!$isValid) {
            $this->error(self::USER_EXISTS);
        }

        return $isValid;
    }

}
