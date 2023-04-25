<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 */
class Account
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="account", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\OneToOne(targetEntity=Transaction::class, mappedBy="receiver", cascade={"persist", "remove"})
     */
    private $transac;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getTransac(): ?Transaction
    {
        return $this->transac;
    }

    public function setTransac(Transaction $transac): self
    {
        // set the owning side of the relation if necessary
        if ($transac->getReceiver() !== $this) {
            $transac->setReceiver($this);
        }

        $this->transac = $transac;

        return $this;
    }
    public function __toString(): string
    {
        return $this->user->getUsername();
    }
}
