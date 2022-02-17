<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\CartBancaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CartBancaireRepository::class)
 */
class CartBancaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="integer")
     * @Assert\Type("integer")
     *
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Type("string")
     *
     *
     */
    private $nomcomplet;

    /**
     * @ORM\Column(type="string")
     *
     *
     */
    private $datexp;

    /**
     * @ORM\Column(type="integer", length=255)
     * @Assert\Type("integer")
     * @Assert\Range(min = 100, max = 999, notInRangeMessage = "Cvv must content 3 Numbers")
     *
     */
    private $cvv;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNomcomplet(): ?string
    {
        return $this->nomcomplet;
    }

    public function setNomcomplet(string $nomcomplet): self
    {
        $this->nomcomplet = $nomcomplet;

        return $this;
    }

    public function getDatexp(): ?string
    {
        return $this->datexp;
    }

    public function setDatexp(string $datexp): self
    {
        $this->datexp = $datexp;

        return $this;
    }


    public function getCvv(): ?int
    {
        return $this->cvv;
    }

    public function setCvv(int $cvv): self
    {
        $this->cvv = $cvv;

        return $this;
    }
}
