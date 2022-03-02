<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReponseRepository::class)
 */
class Reponse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *      min = 10,
     *      max = 250,
     *      minMessage = "Le contenu doit comporter au moins 25 caractères",
     *      maxMessage = "Le sujet ne peut pas dépasser 250 caractères"
     * )
     */
    private $contenu;

    /**
     * @ORM\Column(type="date")
     * @var string A "Y-m-d" formatted value
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     * @var string A "H:i:s" formatted value
     */
    private $heure;

    /**
     * @ORM\ManyToOne(targetEntity=Reclamation::class, inversedBy="reponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reclamation;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class)
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $author;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

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

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(?Reclamation $reclamation): self
    {
        $this->reclamation = $reclamation;

        return $this;
    }

    public function getAuthor(): ?Client
    {
        return $this->author;
    }

    public function setAuthor(?Client $author): self
    {
        $this->author = $author;

        return $this;
    }

}
