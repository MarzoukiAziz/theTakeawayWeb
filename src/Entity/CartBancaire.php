<?php

namespace App\Entity;

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
     * @ORM\Column(type="string", length=26)
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nomcomplet;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $datexp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cvv;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
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

    public function getCvv(): ?string
    {
        return $this->cvv;
    }

    public function setCvv(string $cvv): self
    {
        $this->cvv = $cvv;

        return $this;
    }
}
