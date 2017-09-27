<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks
 */
abstract class BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="bigint", options={"unsigned"=true})
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean", name="isActive", options={"default" = true})
     */
    protected $isActive = true;

    /**
     * @ORM\Column(type="boolean", name="isProtected", options={"default" = false})
     */
    protected $isProtected = false;

    /**
     * @ORM\Column(type="datetime", name="createdAt", nullable=true)
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", name="updatedAt", nullable=true)
     */
    protected $updatedAt;

    /**
     * @ORM\PreUpdate
     */
    public function updatedAt()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PrePersist
     */
    public function createdAt()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Return entity id
     *
     * @return int
     */
    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive ? true : false;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->getIsActive();
    }

    /**
     * @param bool $bool
     */
    public function setIsActive(bool $bool)
    {
        $this->isActive = $bool ? true : false;
    }

    /**
     * @return bool
     */
    public function getIsProtected()
    {
        return $this->isProtected ? true : false;
    }

    /**
     * @return bool
     */
    public function isProtected()
    {
        return $this->getIsProtected();
    }

    /**
     * @param bool $bool
     */
    public function setIsProtected(bool $bool)
    {
        $this->isProtected = $bool ? true : false;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return null|\DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

}
