<?php

namespace App\Entity;

use App\Repository\CreditRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CreditRepository::class)]
class Credit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\ManyToMany(targetEntity: Mois::class, inversedBy: 'credits')]
    private Collection $id_mois;

    #[ORM\ManyToOne(inversedBy: 'credits')]
    private ?Categories $id_category = null;

    public function __construct()
    {
        $this->id_mois = new ArrayCollection();
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

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;
        return $this;
    }

    public function __toString(){
        return $this->title; // Remplacer champ par une propriété "string" de l'entité
    }

    /**
     * @return Collection<int, Mois>
     */
    public function getIdMois(): Collection
    {
        return $this->id_mois;
    }

    public function addIdMoi(Mois $idMoi): self
    {
        if (!$this->id_mois->contains($idMoi)) {
            $this->id_mois->add($idMoi);
        }

        return $this;
    }

    public function removeIdMoi(Mois $idMoi): self
    {
        $this->id_mois->removeElement($idMoi);

        return $this;
    }

    public function getIdCategory(): ?Categories
    {
        return $this->id_category;
    }

    public function setIdCategory(?Categories $id_category): self
    {
        $this->id_category = $id_category;

        return $this;
    }

}
