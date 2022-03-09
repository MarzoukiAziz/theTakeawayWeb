<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\ManyToOne(targetEntity=Restaurant::class)
     */
    private $restaurant;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $methode;

    /**
     * @ORM\Column(type="integer")
     */
    private $pointUtilisees;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statutPaiement;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="commande")
     */
    private $reservation;

    /**
     * @ORM\OneToMany(targetEntity=ElementDetails::class, mappedBy="commande", orphanRemoval=true)
     */
    private $details;

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
        $this->details = new ArrayCollection();
    }
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



    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurantId): self
    {
        $this->restaurant = $restaurantId;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClientId(?Client $clientId): self
    {
        $this->client = $clientId;

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



    public function getStatutPaiement(): ?string
    {
        return $this->statutPaiement;
    }

    public function setStatutPaiement(string $statutPaiement): self
    {
        $this->statutPaiement = $statutPaiement;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation[] = $reservation;
            $reservation->setCommande($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getCommande() === $this) {
                $reservation->setCommande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ElementDetails[]
     */
    public function getDetails(): Collection
    {
        return $this->details;
    }

    public function addDetail(ElementDetails $detail): self
    {
        if (!$this->details->contains($detail)) {
            $this->details[] = $detail;
            $detail->setCommande($this);
        }

        return $this;
    }

    public function removeDetail(ElementDetails $detail): self
    {
        if ($this->details->removeElement($detail)) {
            // set the owning side to null (unless already changed)
            if ($detail->getCommande() === $this) {
                $detail->setCommande(null);
            }
        }

        return $this;
    }

    public function toString():String{
        return "commande n° ".strval($this->id)."      prix ".strval($this->getPrixTotal())."dt" ;
    }
}
