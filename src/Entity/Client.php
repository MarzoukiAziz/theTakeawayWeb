<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;






/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 *
 *
 */
class Client implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     *@Assert\NotBlank
     */
    private $email;




    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=240, nullable=true)
     */
    private $password;
 

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     *@Assert\Type("String")
     */
    private $nom;
 

    /**
     *
     * @ORM\Column(type="string", length=100 , nullable=true)
     * @Assert\Type("String")
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank
     * @Assert\Range(min = 1000000, max = 99999999, notInRangeMessage = "Phone must content 8 Numbers")
     * @Assert\Type("integer")
     */
    private $num_tel;
    
 
    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type("integer")
     *
     */
    private $points;

    /**
     * @ORM\ManyToMany(targetEntity=Restaurant::class)
     */
    private $restaurant;

    /**
     * @ORM\Column(type="datetime" , nullable=true)
     */
    private $Date_curent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebookID;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebookAccessToken;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $githubID;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $githubAccessToken;

    /**
     * @ORM\Column(type="boolean", length=255, nullable=true)
     */
    private $IsVerified;





    public function __construct()
    {
        $this->restaurant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->nom;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    


    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }

    /**
     * @return Collection|Restaurant[]
     */
    public function getRestaurant(): Collection
    {
        return $this->restaurant;
    }

    public function addRestaurant(Restaurant $restaurant): self
    {
        if (!$this->restaurant->contains($restaurant)) {
            $this->restaurant[] = $restaurant;
        }

        return $this;
    }

    public function removeRestaurant(Restaurant $restaurant): self
    {
        $this->restaurant->removeElement($restaurant);

        return $this;
    }

    public function getDateCurent(): ?\DateTimeInterface
    {
        return $this->Date_curent;
    }

    public function setDateCurent(\DateTimeInterface $Date_curent): self
    {
        $this->Date_curent = $Date_curent;

        return $this;
    }
    public function getNumTel(): ?int
    {
        return $this->num_tel;
    }

    public function setNumTel(int $num_tel): self
    {
        $this->num_tel = $num_tel;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFacebookAccessToken()
    {
        return $this->facebookAccessToken;
    }

    /**
     * @param mixed $facebookAccessToken
     */
    public function setFacebookAccessToken($facebookAccessToken): void
    {
        $this->facebookAccessToken = $facebookAccessToken;
    }

    /**
     * @return mixed
     */
    public function getFacebookID()
    {
        return $this->facebookID;
    }

    /**
     * @param mixed $facebookID
     */
    public function setFacebookID($facebookID): void
    {
        $this->facebookID = $facebookID;
    }

    /**
     * @return mixed
     */
    public function getGithubID()
    {
        return $this->githubID;
    }

    /**
     * @param mixed $githubID
     */
    public function setGithubID($githubID): void
    {
        $this->githubID = $githubID;
    }

    /**
     * @return mixed
     */
    public function getGithubAccessToken()
    {
        return $this->githubAccessToken;
    }

    /**
     * @param mixed $githubAccessToken
     */
    public function setGithubAccessToken($githubAccessToken): void
    {
        $this->githubAccessToken = $githubAccessToken;
    }

    /**
     * @return mixed
     */
    public function getIsVerified()
    {
        return $this->IsVerified;
    }

    /**
     * @param mixed $IsVerified
     */
    public function setIsVerified($IsVerified): void
    {
        $this->IsVerified = $IsVerified;
    }
}
