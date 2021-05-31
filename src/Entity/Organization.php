<?php

namespace App\Entity;

use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrganizationRepository::class)
 */
class Organization
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $coded;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="organization")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Login::class, mappedBy="organization", orphanRemoval=true)
     */
    private $login;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->login = new ArrayCollection();
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

    public function getCoded(): ?string
    {
        return $this->coded;
    }

    public function setCoded(string $coded): self
    {
        $this->coded = $coded;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setOrganization($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getOrganization() === $this) {
                $user->setOrganization(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Login[]
     */
    public function getLogin(): Collection
    {
        return $this->login;
    }

    public function addLogin(Login $login): self
    {
        if (!$this->login->contains($login)) {
            $this->login[] = $login;
            $login->setOrganization($this);
        }

        return $this;
    }

    public function removeLogin(Login $login): self
    {
        if ($this->login->removeElement($login)) {
            // set the owning side to null (unless already changed)
            if ($login->getOrganization() === $this) {
                $login->setOrganization(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Agency[]
     */
    public function getAgency(): Collection
    {
        return $this->agency;
    }

    public function addAgency(Agency $agency): self
    {
        if (!$this->agency->contains($agency)) {
            $this->agency[] = $agency;
            $agency->setOrganization($this);
        }

        return $this;
    }

    public function removeAgency(Agency $agency): self
    {
        if ($this->agency->removeElement($agency)) {
            // set the owning side to null (unless already changed)
            if ($agency->getOrganization() === $this) {
                $agency->setOrganization(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CrisisRoom[]
     */
    public function getCrisisRoom(): Collection
    {
        return $this->crisisroom;
    }

    public function addCrisisRoom(CrisisRoom $crisisroom): self
    {
        if (!$this->crisisroom->contains($crisisroom)) {
            $this->crisisroom[] = $crisisroom;
            $crisisroom->setOrganization($this);
        }

        return $this;
    }

    public function removeCrisisRoom(CrisisRoom $crisisroom): self
    {
        if ($this->crisisroom->removeElement($crisisroom)) {
            // set the owning side to null (unless already changed)
            if ($crisisroom->getOrganization() === $this) {
                $crisisroom->setOrganization(null);
            }
        }

        return $this;
    }
}
