<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class PropertySearch
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Fournisseur")
     */
    private $fournisseur;


    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }


    public function setFournisseur(?Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;
        return $this;
    }





}