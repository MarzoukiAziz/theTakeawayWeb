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
     * @ORM\ManyToOne(targetEntity=MenuElement::class)
     * @ORM\JoinColumn(nullable=false)
     */
    public $elementId;

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class, inversedBy="details")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $options;

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



    public function getElementId(): ?MenuElement
    {
        return $this->elementId;
    }

    public function setElementId(?MenuElement $elementId): self
    {
        $this->elementId = $elementId;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getOptions(): ?string
    {
        return $this->options;
    }

    public function setOptions(string $options): self
    {
        $this->options = $options;

        return $this;
    }
}
