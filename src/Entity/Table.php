<?php

namespace App\Entity;

use App\Repository\TableRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TableRepository::class)
 * @ORM\Table(name="`table`")
 */
class Table
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 700,
     *      notInRangeMessage = "PosX entre 0 et 700",
     * )
     */
    private $posX;

    /**
     * @ORM\Column(type="integer")
     *      * @Assert\Range(
     *      min = 0,
     *      max = 450,
     *      notInRangeMessage = "PosY entre 0 et 450",
     * )
     */
    private $posY;


    /**
     * @ORM\Column(type="integer")
     *@Assert\Range(
     *      min = 1,
     *      max = 20,
     *      notInRangeMessage = "Nombre de places entre 1 et 20",
     * )
     *
     */
    private $nbPalces;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 50,
     *      notInRangeMessage = "Nombre de places entre 1 et 20",
     * )
     */
    private $numero;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class, inversedBy="tables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $restaurantId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosX(): ?int
    {
        return $this->posX;
    }

    public function setPosX(int $posX): self
    {
        $this->posX = $posX;

        return $this;
    }

    public function getPosY(): ?int
    {
        return $this->posY;
    }

    public function setPosY(int $posY): self
    {
        $this->posY = $posY;

        return $this;
    }


    public function getNbPalces(): ?int
    {
        return $this->nbPalces;
    }

    public function setNbPalces(int $nbPalces): self
    {
        $this->nbPalces = $nbPalces;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

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
}
