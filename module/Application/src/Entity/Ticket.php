<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="\Application\Repository\TicketRepository")
 * @ORM\Table(name="Ticket")
 * @ORM\HasLifecycleCallbacks
 */
class Ticket extends \Application\Entity\BaseEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\Concert", inversedBy="tickets")
     * @ORM\JoinColumn(name="concert_id", referencedColumnName="id")
     * @var Concert $concert
     */
    protected $concert;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\Customer", inversedBy="tickets")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     * @var Customer $customer
     */
    protected $customer;

    /**
     * @return Concert
     */
    public function getConcert()
    {
        return $this->concert;
    }

    /**
     * @param Concert $concert
     */
    public function setConcert($concert)
    {
        $this->concert = $concert;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }
}
