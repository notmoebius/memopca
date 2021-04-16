<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $mobilenumber;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $phonenumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $structure;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $floor;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity=Role::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @ORM\ManyToOne(targetEntity=Grade::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $grade;

    /**
     * @ORM\ManyToOne(targetEntity=Directory::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $directory;

    /**
     * @ORM\OneToMany(targetEntity=Warn::class, mappedBy="previens", orphanRemoval=true)
     */
    private $warns;

    /**
     * @ORM\OneToMany(targetEntity=Warn::class, mappedBy="prevenu", orphanRemoval=true)
     */
    private $warned;

    public function __construct()
    {
        $this->warns = new ArrayCollection();
        $this->warned = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getMobilenumber(): ?string
    {
        return $this->mobilenumber;
    }

    public function setMobilenumber(string $mobilenumber): self
    {
        $this->mobilenumber = $mobilenumber;

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(string $phonenumber): self
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getStructure(): ?string
    {
        return $this->structure;
    }

    public function setStructure(string $structure): self
    {
        $this->structure = $structure;

        return $this;
    }

    public function getFloor(): ?string
    {
        return $this->floor;
    }

    public function setFloor(?string $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setRole(Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getGrade(): Grade
    {
        return $this->grade;
    }

    public function setGrade(Grade $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    public function getDirectory(): Directory
    {
        return $this->directory;
    }

    public function setDirectory(Directory $directory): self
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * @return Collection|Warn[]
     */
    public function getWarns(): Collection
    {
        return $this->warns;
    }

    public function addWarn(Warn $warn): self
    {
        if (!$this->warns->contains($warn)) {
            $this->warns[] = $warn;
            $warn->setPreviens($this);
        }

        return $this;
    }

    public function removeWarn(Warn $warn): self
    {
        if ($this->warns->removeElement($warn)) {
            // set the owning side to null (unless already changed)
            if ($warn->getPreviens() === $this) {
                $warn->setPreviens(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Warn[]
     */
    public function getWarned(): Collection
    {
        return $this->warned;
    }

    public function addWarned(Warn $warned): self
    {
        if (!$this->warned->contains($warned)) {
            $this->warned[] = $warned;
            $warned->setPrevenu($this);
        }

        return $this;
    }

    public function removeWarned(Warn $warned): self
    {
        if ($this->warned->removeElement($warned)) {
            // set the owning side to null (unless already changed)
            if ($warned->getPrevenu() === $this) {
                $warned->setPrevenu(null);
            }
        }

        return $this;
    }
}
