<?php

namespace App\Entity;
use App\Entity\Client;
use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;

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
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string" , nullable=true)
     */
    private $heureOuverture;

    /**
     * @ORM\Column(type="string" , nullable=true)
     */
    private $heureFermeture;

    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $architecture;

    /**
     * @ORM\Column(type="string", length=10 , nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="array" , nullable=true)
     */
    private $images = [];

    /**
     * @ORM\OneToMany(targetEntity=Table::class, mappedBy="restaurantId", orphanRemoval=true)
     */
    private $tables;

    /**
     * @ORM\OneToMany(targetEntity=RestaurantFavoris::class, mappedBy="Restaurant")
     */
    private $favoris;

    public function __construct()
    {
        $this->tables = new ArrayCollection();
        $this->favoris = new ArrayCollection();
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

    public function getHeureOuverture():  ?string
    {
        return $this->heureOuverture;
    }

    public function setHeureOuverture(string $heureOuverture): self
    {
        $this->heureOuverture = $heureOuverture;

        return $this;
    }

    public function getHeureFermeture(): ?string
    {
        return $this->heureFermeture;
    }

    public function setHeureFermeture(string $heureFermeture): self
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
