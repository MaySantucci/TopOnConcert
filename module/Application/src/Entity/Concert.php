<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="\Application\Repository\ConcertRepository")
 * @ORM\Table(name="Concert")
 * @ORM\HasLifecycleCallbacks
 */
class Concert extends \Application\Entity\BaseEntity
{
    /**
     * @ORM\Column(type="text", name="artist", nullable=false)
     * @var string $artist
     */
    protected $artist;

    /**
     * @ORM\Column(type="datetime", name="date", nullable=false)
     * @var \DateTime $date
     */
    protected $date;

    /**
     * @ORM\Column(type="float", name="price", nullable=false)
     * @var float $price
     */
    protected $price;

    /**
     * @ORM\Column(type="string", name="location", nullable=false)
     * @var string $location
     */
    protected $location;

    /**
     * @ORM\Column(type="integer", name="availability", nullable=false)
     * @var int $availability
     */
    protected $availability;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\Organizer", inversedBy="concerts")
     * @ORM\JoinColumn(name="organizer_id", referencedColumnName="id")
     * @var Organizer $organizer
     */
    protected $organizer;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Ticket", mappedBy="concert")
     * @ORM\JoinColumn(name="id", referencedColumnName="ticket_id")
     * @var ArrayCollection $tickets
     */
    protected $tickets;

    /**
     * Concert constructor
     */
    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @param string $artist
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return int
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * @param int $availability
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;
    }

    /**
     * @return Organizer
     */
    public function getOrganizer()
    {
        return $this->organizer;
    }

    /**
     * @param Organizer $organizer
     */
    public function setOrganizer($organizer)
    {
        $this->organizer = $organizer;
    }

    /**
     * @return ArrayCollection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * @param ArrayCollection $ticket
     */
    public function addTicket($ticket)
    {
        $this->tickets[] = $ticket;
    }
}