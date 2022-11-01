<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\OneToMany(mappedBy: 'id_category', targetEntity: Credit::class)]
    private Collection $credits;

    #[ORM\OneToMany(mappedBy: 'id_category', targetEntity: Debit::class)]
    private Collection $debits;

    public function __construct()
    {
        $this->credits = new ArrayCollection();
        $this->debits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Credit>
     */
    public function getCredits(): Collection
    {
        return $this->credits;
    }

    public function addCredit(Credit $credit): self
    {
        if (!$this->credits->contains($credit)) {
            $this->credits->add($credit);
            $credit->setIdCategory($this);
        }

        return $this;
    }

    public function removeCredit(Credit $credit): self
    {
        if ($this->credits->removeElement($credit)) {
            // set the owning side to null (unless already changed)
            if ($credit->getIdCategory() === $this) {
                $credit->setIdCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Debit>
     */
    public function getDebits(): Collection
    {
        return $this->debits;
    }

    public function addDebit(Debit $debit): self
    {
        if (!$this->debits->contains($debit)) {
            $this->debits->add($debit);
            $debit->setIdCategory($this);
        }

        return $this;
    }

    public function removeDebit(Debit $debit): self
    {
        if ($this->debits->removeElement($debit)) {
            // set the owning side to null (unless already changed)
            if ($debit->getIdCategory() === $this) {
                $debit->setIdCategory(null);
            }
        }

        return $this;
    }
}
