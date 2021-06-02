<?php

namespace App\Entity;

use App\Repository\CrisisRoomRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CrisisRoomRepository::class)
 */
class CrisisRoom
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $phonenumber;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $faxnumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $plan;

    /**
     * @ORM\ManyToOne(targetEntity=TypeCrisisRoom::class, inversedBy="typeroom")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeCrisisRoom;

    /**
     * @ORM\ManyToOne(targetEntity=Organization::class, inversedBy="organizationCrisisRoom")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organization;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(?string $phonenumber): self
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getFaxnumber(): ?string
    {
        return $this->faxnumber;
    }

    public function setFaxnumber(?string $faxnumber): self
    {
        $this->faxnumber = $faxnumber;

        return $this;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(string $address1): self
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(string $address2): self
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getAddress3(): ?string
    {
        return $this->address3;
    }

    public function setAddress3(?string $address3): self
    {
        $this->address3 = $address3;

        return $this;
    }

    public function getPlan(): ?string
    {
        return $this->plan;
    }

    public function setPlan(string $plan): self
    {
        $this->plan = $plan;

        return $this;
    }

    public function getTypeCrisisRoom(): ?TypeCrisisRoom
    {
        return $this->typeCrisisRoom;
    }

    public function setTypeCrisisRoom(?TypeCrisisRoom $typeCrisisRoom): self
    {
        $this->typeCrisisRoom = $typeCrisisRoom;

        return $this;
    }

    public function getOrganization(): Organization
    {
        return $this->organization;
    }

    public function setOrganization(Organization $organization): self
    {
        $this->organization = $organization;

        return $this;
    }
}
