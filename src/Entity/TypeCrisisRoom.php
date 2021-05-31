<?php

namespace App\Entity;

use App\Repository\TypeCrisisRoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeCrisisRoomRepository::class)
 */
class TypeCrisisRoom
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=CrisisRoom::class, mappedBy="typeCrisisRoom", orphanRemoval=true)
     */
    private $typeroom;

    public function __construct()
    {
        $this->typeroom = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|CrisisRoom[]
     */
    public function getTyperoom(): Collection
    {
        return $this->typeroom;
    }

    public function addTyperoom(CrisisRoom $typeroom): self
    {
        if (!$this->typeroom->contains($typeroom)) {
            $this->typeroom[] = $typeroom;
            $typeroom->setTypeCrisisRoom($this);
        }

        return $this;
    }

    public function removeTyperoom(CrisisRoom $typeroom): self
    {
        if ($this->typeroom->removeElement($typeroom)) {
            // set the owning side to null (unless already changed)
            if ($typeroom->getTypeCrisisRoom() === $this) {
                $typeroom->setTypeCrisisRoom(null);
            }
        }

        return $this;
    }
}
