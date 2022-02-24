<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 */
class Facture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     *  @Assert\Positive

     */
    private $quantite;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     */
    private $heure;

    /**
     * @ORM\Column(type="float")
     *   @Assert\Positive

     */
    private $prixUnitaire;

    /**
     * @ORM\ManyToOne(targetEntity=Fournisseur::class)
     */
    private $fournisseurId;

    /**
     * @ORM\ManyToOne(targetEntity=Ingrediant::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $ingrediantId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): self
    {
        $this->heure = $heure;

        return $this;
    }

    public function getPrixUnitaire(): ?float
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(float $prixUnitaire): self
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    public function getFournisseurId(): ?Fournisseur
    {
        return $this->fournisseurId;
    }

    public function setFournisseurId(?Fournisseur $fournisseurId): self
    {
        $this->fournisseurId = $fournisseurId;

        return $this;
    }

    public function getIngrediantId(): ?Ingrediant
    {
        return $this->ingrediantId;
    }

    public function setIngrediantId(?Ingrediant $ingrediantId): self
    {
        $this->ingrediantId = $ingrediantId;

        return $this;
    }
}
