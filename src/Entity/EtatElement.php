<?php

namespace App\Entity;

use App\Repository\EtatElementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtatElementRepository::class)
 */
class EtatElement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $tempsAttente;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disponibilite;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $restaurant;

    /**
     * @ORM\ManyToOne(targetEntity=MenuElement::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $element;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTempsAttente(): ?int
    {
        return $this->tempsAttente;
    }

    public function setTempsAttente(int $tempsAttente): self
    {
        $this->tempsAttente = $tempsAttente;

        return $this;
    }

    public function getDisponibilite(): ?bool
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(bool $disponibilite): self
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    public function getElement(): ?MenuElement
    {
        return $this->element;
    }

    public function setElement(?MenuElement $element): self
    {
        $this->element = $element;

        return $this;
    }
}
