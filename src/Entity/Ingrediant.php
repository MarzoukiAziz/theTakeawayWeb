<?php

namespace App\Entity;

use App\Repository\IngrediantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IngrediantRepository::class)
 */
class Ingrediant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $restaurant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
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

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurantId;
    }

    public function setRestaurant(?Restaurant $restaurantId): self
    {
        $this->restaurantId = $restaurantId;

        return $this;
    }
}
