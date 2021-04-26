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
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="memos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="user_inform2")
     */
    private $inform2;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="user_informed2")
     */
    private $informed2;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="user_inform1")
     */
    private $inform1;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="user_informed1")
     */
    private $informed1;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getInform2(): ?user
    {
        return $this->inform2;
    }

    public function setInform2(?user $inform2): self
    {
        $this->inform2 = $inform2;

        return $this;
    }

    public function getInformed2(): ?user
    {
        return $this->informed2;
    }

    public function setInformed2(?user $informed2): self
    {
        $this->informed2 = $informed2;

        return $this;
    }

    public function getInform1(): ?user
    {
        return $this->inform1;
    }

    public function setInform1(?user $inform1): self
    {
        $this->inform1 = $inform1;

        return $this;
    }

    public function getInformed1(): ?user
    {
        return $this->informed1;
    }

    public function setInformed1(?user $informed1): self
    {
        $this->informed1 = $informed1;

        return $this;
    }
}
