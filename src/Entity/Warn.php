<?php

namespace App\Entity;

use App\Repository\WarnRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WarnRepository::class)
 */
class Warn
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="warns")
     * @ORM\JoinColumn(nullable=false)
     */
    private $previens;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="warned")
     * @ORM\JoinColumn(nullable=false)
     */
    private $prevenu;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPreviens(): ?User
    {
        return $this->previens;
    }

    public function setPreviens(?User $previens): self
    {
        $this->previens = $previens;

        return $this;
    }

    public function getPrevenu(): ?user
    {
        return $this->prevenu;
    }

    public function setPrevenu(?user $prevenu): self
    {
        $this->prevenu = $prevenu;

        return $this;
    }
}
