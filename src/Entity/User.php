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
    private $profession;

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
     * @ORM\ManyToOne(targetEntity=Organization::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organization;

    /**
     * @ORM\ManyToOne(targetEntity=Login::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $login;

    /**
     * @ORM\OneToMany(targetEntity=Memo::class, mappedBy="users", orphanRemoval=true)
     */
    private $memos;

    /**
     * @ORM\OneToMany(targetEntity=Memo::class, mappedBy="inform2")
     */
    private $user_inform2;

    /**
     * @ORM\OneToMany(targetEntity=Memo::class, mappedBy="informed2")
     */
    private $user_informed2;

    /**
     * @ORM\OneToMany(targetEntity=Memo::class, mappedBy="inform1")
     */
    private $user_inform1;

    /**
     * @ORM\OneToMany(targetEntity=Memo::class, mappedBy="informed1")
     */
    private $user_informed1;

    public function __construct()
    {
        $this->inform = new ArrayCollection();
        $this->informed = new ArrayCollection();
        $this->memos = new ArrayCollection();
        $this->user_inform2 = new ArrayCollection();
        $this->user_informed2 = new ArrayCollection();
        $this->user_inform1 = new ArrayCollection();
        $this->user_informed1 = new ArrayCollection();
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

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(string $profession): self
    {
        $this->profession = $profession;

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

    public function getUserInform(): ?Memo
    {
        return $this->user_inform;
    }

    public function setUserInform(?Memo $user_inform): self
    {
        $this->user_inform = $user_inform;

        return $this;
    }

    public function getUserInformed(): ?Memo
    {
        return $this->user_informed;
    }

    public function setUserInformed(?Memo $user_informed): self
    {
        $this->user_informed = $user_informed;

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

    public function getLogin(): ?Login
    {
        return $this->login;
    }

    public function setLogin(?Login $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return Collection|Memo[]
     */
    public function getMemos(): Collection
    {
        return $this->memos;
    }

    public function addMemo(Memo $memo): self
    {
        if (!$this->memos->contains($memo)) {
            $this->memos[] = $memo;
            $memo->setUsers($this);
        }

        return $this;
    }

    public function removeMemo(Memo $memo): self
    {
        if ($this->memos->removeElement($memo)) {
            // set the owning side to null (unless already changed)
            if ($memo->getUsers() === $this) {
                $memo->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Memo[]
     */
    public function getUserInform2(): Collection
    {
        return $this->user_inform2;
    }

    public function addUserInform2(Memo $userInform2): self
    {
        if (!$this->user_inform2->contains($userInform2)) {
            $this->user_inform2[] = $userInform2;
            $userInform2->setInform2($this);
        }

        return $this;
    }

    public function removeUserInform2(Memo $userInform2): self
    {
        if ($this->user_inform2->removeElement($userInform2)) {
            // set the owning side to null (unless already changed)
            if ($userInform2->getInform2() === $this) {
                $userInform2->setInform2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Memo[]
     */
    public function getUserInformed2(): Collection
    {
        return $this->user_informed2;
    }

    public function addUserInformed2(Memo $userInformed2): self
    {
        if (!$this->user_informed2->contains($userInformed2)) {
            $this->user_informed2[] = $userInformed2;
            $userInformed2->setInformed2($this);
        }

        return $this;
    }

    public function removeUserInformed2(Memo $userInformed2): self
    {
        if ($this->user_informed2->removeElement($userInformed2)) {
            // set the owning side to null (unless already changed)
            if ($userInformed2->getInformed2() === $this) {
                $userInformed2->setInformed2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Memo[]
     */
    public function getUserInform1(): Collection
    {
        return $this->user_inform1;
    }

    public function addUserInform1(Memo $userInform1): self
    {
        if (!$this->user_inform1->contains($userInform1)) {
            $this->user_inform1[] = $userInform1;
            $userInform1->setInform1($this);
        }

        return $this;
    }

    public function removeUserInform1(Memo $userInform1): self
    {
        if ($this->user_inform1->removeElement($userInform1)) {
            // set the owning side to null (unless already changed)
            if ($userInform1->getInform1() === $this) {
                $userInform1->setInform1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Memo[]
     */
    public function getUserInformed1(): Collection
    {
        return $this->user_informed1;
    }

    public function addUserInformed1(Memo $userInformed1): self
    {
        if (!$this->user_informed1->contains($userInformed1)) {
            $this->user_informed1[] = $userInformed1;
            $userInformed1->setInformed1($this);
        }

        return $this;
    }

    public function removeUserInformed1(Memo $userInformed1): self
    {
        if ($this->user_informed1->removeElement($userInformed1)) {
            // set the owning side to null (unless already changed)
            if ($userInformed1->getInformed1() === $this) {
                $userInformed1->setInformed1(null);
            }
        }

        return $this;
    }
}
