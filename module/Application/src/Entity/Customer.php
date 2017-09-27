<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="\Application\Repository\CustomerRepository")
 * @ORM\Table(name="Customer")
 * @ORM\HasLifecycleCallbacks
 */
class Customer extends \Application\Entity\BaseEntity
{
    /**
     * @ORM\Column(type="text", name="fullName", nullable=false)
     * @var string $fullName
     */
    protected $fullName;

    /**
     * @ORM\Column(type="string", name="email", unique=true, nullable=false)
     * @var string $email
     */
    protected $email;

    /**
     * @ORM\Column(type="string", name="password", nullable=false)
     * @var string $password
     */
    protected $password;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Ticket", mappedBy="customer")
     * @ORM\JoinColumn(name="id", referencedColumnName="customer_id")
     * @var ArrayCollection $tickets
     */
    protected $tickets;

    /**
     * Customer constructor
     */
    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return ArrayCollection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * @param Ticket $ticket
     */
    public function addConcert($ticket)
    {
        $this->tickets[] = $ticket;
    }
}