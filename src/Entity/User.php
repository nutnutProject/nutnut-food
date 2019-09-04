<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank()
     * @Assert\length(min=3)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank()
     * @Assert\length(min=3)
     */
    private $lastname;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     * @Assert\Date
     */
    private $birthdate;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\length(min=8)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\length(min=5)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     * @Assert\length(min=2)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=5)
     * @Assert\NotBlank()
     * @Assert\Positive
     * @Assert\length(min=5)
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotBlank()
     * @Assert\Positive
     * @Assert\length(min=10)
     */
    private $telephone;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\length(min=10)
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $pwd_token;

    /**
     * @ORM\Column(type="text")
     */
    private $activateToken;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\length(min=10)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Recette", mappedBy="user", orphanRemoval=true)
     */
    private $recettes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserRequest", mappedBy="user", orphanRemoval=true)
     */
    private $userRequests;

    /**
     * @ORM\Column(type="integer")
     */
    private $pwd_token_expire;

    /**
     * @ORM\Column(type="integer")
     */
    private $activateToken_expire;

    /**
     * @ORM\Column(type="boolean")
     */
    private $account_activate;

    public function __construct()
    {
        $this->recettes = new ArrayCollection();
        $this->userRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPwdToken(): ?string
    {
        return $this->pwd_token;
    }

    public function setPwdToken(string $pwd_token): self
    {
        $this->pwd_token = $pwd_token;

        return $this;
    }

    public function getActivateToken(): ?string
    {
        return $this->activateToken;
    }

    public function setActivateToken(string $activateToken): self
    {
        $this->activateToken = $activateToken;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getRoles(): array
    {
        if (empty($this->roles)) {
            $this->roles[] = 'ROLE_USER';
        }

        return $this->roles;
    }

    public function setRoles($roles)
    {
        if (!is_array($roles)) {
            $this->roles[] = $roles;

            return;
        }

        $this->roles = $roles;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        return null;
    }

    /**
     * @return Collection|Recette[]
     */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recette $recette): self
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes[] = $recette;
            $recette->setUser($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): self
    {
        if ($this->recettes->contains($recette)) {
            $this->recettes->removeElement($recette);
            // set the owning side to null (unless already changed)
            if ($recette->getUser() === $this) {
                $recette->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserRequest[]
     */
    public function getUserRequests(): Collection
    {
        return $this->userRequests;
    }

    public function addUserRequest(UserRequest $userRequest): self
    {
        if (!$this->userRequests->contains($userRequest)) {
            $this->userRequests[] = $userRequest;
            $userRequest->setUser($this);
        }

        return $this;
    }

    public function removeUserRequest(UserRequest $userRequest): self
    {
        if ($this->userRequests->contains($userRequest)) {
            $this->userRequests->removeElement($userRequest);
            // set the owning side to null (unless already changed)
            if ($userRequest->getUser() === $this) {
                $userRequest->setUser(null);
            }
        }

        return $this;
    }

    public function getPwdTokenExpire(): ?int
    {
        return $this->pwd_token_expire;
    }

    public function setPwdTokenExpire(int $pwd_token_expire): self
    {
        $this->pwd_token_expire = $pwd_token_expire;

        return $this;
    }

    public function getActivateTokenExpire(): ?int
    {
        return $this->activateToken_expire;
    }

    public function setActivateTokenExpire(int $activateToken_expire): self
    {
        $this->activateToken_expire = $activateToken_expire;

        return $this;
    }

    public function getAccountActivate(): ?bool
    {
        return $this->account_activate;
    }

    public function setAccountActivate(bool $account_activate): self
    {
        $this->account_activate = $account_activate;

        return $this;
    }


    // Sert à convertir les entités en string IMPERATIF pour EasyAdminBundle
    public function __toString()
    {
        return $this->firstname;
    }

}
