<?php

namespace App\Entity;

use App\Repository\ElementDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ElementDetailsRepository::class)
 */
class ElementDetails
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
    private $quantite;

    /**
     * @ORM\Column(type="array")
     */
    private $options = [];

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $commandeId;

    /**
     * @ORM\ManyToOne(targetEntity=MenuElement::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $elementId;

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

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getCommandeId(): ?Commande
    {
        return $this->commandeId;
    }

    public function setCommandeId(?Commande $commandeId): self
    {
        $this->commandeId = $commandeId;

        return $this;
    }

    public function getElementId(): ?MenuElement
    {
        return $this->elementId;
    }

    public function setElementId(?MenuElement $elementId): self
    {
        $this->elementId = $elementId;

        return $this;
    }
}
