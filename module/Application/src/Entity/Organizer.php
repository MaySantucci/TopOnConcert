<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="\Application\Repository\OrganizerRepository")
 * @ORM\Table(name="Organizer")
 * @ORM\HasLifecycleCallbacks
 */
class Organizer extends \Application\Entity\BaseEntity
{
    /**
     * @ORM\Column(type="text", name="fullName", nullable=false)
     * @var string $fullName
     */
    protected $fullName;

    /**
     * @ORM\Column(type="text", name="company", nullable=true)
     * @var string $company
     */
    protected $company;

    /**
     * @ORM\Column(type="text", name="vat", nullable=true)
     * @var string $vat
     */
    protected $vat;

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
     * @ORM\OneToMany(targetEntity="Concert", mappedBy="organizer")
     * @ORM\JoinColumn(name="id", referencedColumnName="organizer_id")
     * @var ArrayCollection $concerts
     */
    protected $concerts;

    /**
     * Organizer constructor
     */
    public function __construct()
    {
        $this->concerts = new ArrayCollection();
    }
    
    
    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->id;
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
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return string
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param string $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
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
    public function getConcerts()
    {
        return $this->concerts;
    }

    /**
     * @param Concert $concert
     */
    public function addConcert($concert)
    {
        $this->concerts[] = $concert;
    }
}
