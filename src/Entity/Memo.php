<?php

namespace App\Entity;

use App\Repository\MemoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MemoRepository::class)
 */
class Memo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="inform")
     */
    private $inform;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="informed")
     */
    private $informed;

    public function __construct()
    {
        $this->inform = new ArrayCollection();
        $this->informed = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getInform(): Collection
    {
        return $this->inform;
    }

    public function addInform(User $inform): self
    {
        if (!$this->inform->contains($inform)) {
            $this->inform[] = $inform;
            $inform->setUserInform($this);
        }

        return $this;
    }

    public function removeInform(User $inform): self
    {
        if ($this->inform->removeElement($inform)) {
            // set the owning side to null (unless already changed)
            if ($inform->getUserInform() === $this) {
                $inform->setUserInform(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getInformed(): Collection
    {
        return $this->informed;
    }

    public function addInformed(User $informed): self
    {
        if (!$this->informed->contains($informed)) {
            $this->informed[] = $informed;
            $informed->setUserInformed($this);
        }

        return $this;
    }

    public function removeInformed(User $informed): self
    {
        if ($this->informed->removeElement($informed)) {
            // set the owning side to null (unless already changed)
            if ($informed->getUserInformed() === $this) {
                $informed->setUserInformed(null);
            }
        }

        return $this;
    }
}
