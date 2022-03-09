<?php

namespace App\Entity;
use App\Entity\Client;
use App\Repository\RestaurantFavorisRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RestaurantFavorisRepository::class)
 */
class RestaurantFavoris
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class, inversedBy="favoris")
     */
    private $Restaurant;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="favoris")
     */
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->Restaurant;
    }

    public function setRestaurant(?Restaurant $Restaurant): self
    {
        $this->Restaurant = $Restaurant;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
