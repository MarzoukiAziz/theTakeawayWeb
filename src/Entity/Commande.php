<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $prixTotal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     */
    private $heure;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class)
     */
    private $restaurantId;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $clientId;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $employeCharge;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $methode;

    /**
     * @ORM\Column(type="integer")
     */
    private $pointUtilisees;

    /**
     * @ORM\ManyToOne(targetEntity=CartBancaire::class)
     */
    private $carteId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statutPaiement;
    public function getId(): ?int
    {

        return $this->id;
    }

    public function getPrixTotal(): ?float
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(float $prixTotal): self
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

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

    public function getRestaurantId(): ?Restaurant
    {
        return $this->restaurantId;
    }

    public function setRestaurantId(?Restaurant $restaurantId): self
    {
        $this->restaurantId = $restaurantId;

        return $this;
    }

    public function getClientId(): ?Client
    {
        return $this->clientId;
    }

    public function setClientId(?Client $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getEmployeCharge(): ?Client
    {
        return $this->employeCharge;
    }

    public function setEmployeCharge(?Client $employeCharge): self
    {
        $this->employeCharge = $employeCharge;

        return $this;
    }

    public function getMethode(): ?string
    {
        return $this->methode;
    }

    public function setMethode(string $methode): self
    {
        $this->methode = $methode;

        return $this;
    }

    public function getPointUtilisees(): ?int
    {
        return $this->pointUtilisees;
    }

    public function setPointUtilisees(int $pointUtilisees): self
    {
        $this->pointUtilisees = $pointUtilisees;

        return $this;
    }

    public function getCarteId(): ?CarteBancaire
    {
        return $this->carteId;
    }

    public function setCarteId(?CarteBancaire $carteId): self
    {
        $this->carteId = $carteId;

        return $this;
    }

    public function getStatutPaiement(): ?string
    {
        return $this->statutPaiement;
    }

    public function setStatutPaiement(string $statutPaiement): self
    {
        $this->statutPaiement = $statutPaiement;

        return $this;
    }
}
