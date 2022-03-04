<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 */
class Promotion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\Type("\DateTime")
     * @Assert\GreaterThan("today")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     * @Assert\Type("\DateTime")
     * @Assert\GreaterThan("today")
     *  @Assert\GreaterThan(propertyPath="dateDebut")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="time")
     */
    private $heureDebut;

    /**
     * @ORM\Column(type="time")
     * @Assert\GreaterThan(propertyPath="heureDebut")
     */
    private $heureFin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $banner;

    /**
     * @ORM\ManyToOne(targetEntity=MenuElement::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $element;

    /**
     * @ORM\Column(type="float")
     * @Assert\Positive
     */
    private $prixPromo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(\DateTimeInterface $heureDebut): self
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heureFin;
    }

    public function setHeureFin(\DateTimeInterface $heureFin): self
    {
        $this->heureFin = $heureFin;

        return $this;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBanner(string $banner): self
    {
        $this->banner = $banner;

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

    public function getPrixPromo(): ?float
    {
        return $this->prixPromo;
    }

    public function setPrixPromo(float $prixPromo): self
    {
        $this->prixPromo = $prixPromo;

        return $this;
    }
}
