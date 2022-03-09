<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=RestaurantRepository::class)
 */
class Restaurant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  * @Assert\NotBlank(message="Nom is required")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     *  * @Assert\NotBlank(message="Adresse is required")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     *  * @Assert\NotBlank(message="description is required")
     */
    private $description;

    /**
     * @ORM\Column(type="time")
     */
    private $heureOuverture;

    /**
     * @ORM\Column(type="time")
     * @Assert\GreaterThan(propertyPath="heureOuverture")
     */
    private $heureFermeture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $architecture;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "le numero est invalide verifer la longeur",
     *      maxMessage = "le numero est invalide verifer la longeur"
     * )
     */
    private $telephone;

    /**
     * @ORM\Column(type="array")
     */
    private $images = [];

    /**
     * @ORM\OneToMany(targetEntity=RestaurantFavoris::class, mappedBy="Restaurant")
     */
    private $favoris;


    /**
     * @ORM\OneToMany(targetEntity=Table::class, mappedBy="restaurantId", orphanRemoval=true)
     */
    private $tables;

    public function __construct()
    {
        $this->tables = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getHeureOuverture(): ?\DateTimeInterface
    {
        return $this->heureOuverture;
    }

    public function setHeureOuverture(\DateTimeInterface $heureOuverture): self
    {
        $this->heureOuverture = $heureOuverture;

        return $this;
    }

    public function getHeureFermeture(): ?\DateTimeInterface
    {
        return $this->heureFermeture;
    }

    public function setHeureFermeture(\DateTimeInterface $heureFermeture): self
    {
        $this->heureFermeture = $heureFermeture;

        return $this;
    }

    public function getArchitecture(): ?string
    {
        return $this->architecture;
    }

    public function setArchitecture(string $architecture): self
    {
        $this->architecture = $architecture;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(array $images): self
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @return Collection|Table[]
     */
    public function getTables(): Collection
    {
        return $this->tables;
    }

    public function addTable(Table $table): self
    {
        if (!$this->tables->contains($table)) {
            $this->tables[] = $table;
            $table->setRestaurantId($this);
        }

        return $this;
    }

    public function removeTable(Table $table): self
    {
        if ($this->tables->removeElement($table)) {
            // set the owning side to null (unless already changed)
            if ($table->getRestaurantId() === $this) {
                $table->setRestaurantId(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection<int, RestaurantFavoris>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(RestaurantFavoris $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
            $favori->setRestaurant($this);
        }

        return $this;
    }

    public function removeFavori(RestaurantFavoris $favori): self
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getRestaurant() === $this) {
                $favori->setRestaurant(null);
            }
        }

        return $this;
    }
    /**
     * @param Client $client
     * @return boolean
     *
     */

    public function isLikedByUser(Client $client) : bool
    {
        foreach ($this->favoris as $favoris)
        {
            if($favoris->getClient() == $client) return true ;

        }
        return false;
    }

}