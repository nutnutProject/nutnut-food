<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecetteRepository")
 */
class Recette
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     * @Assert\Length(min=8)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(min=15)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $online;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation_date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="note", orphanRemoval=true)
     */
    private $notes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Diet", inversedBy="recettes")
     */
    private $diet;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="recettes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="recettes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ingredient", mappedBy="recette", orphanRemoval=true)
     */
    private $ingredients;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserRequest", mappedBy="recette", orphanRemoval=true)
     */
    private $userRequests;

    /**
     * @ORM\Column(type="float")
     */
    private $note;

    public function __construct()
    {
        $this->diet = new ArrayCollection();
        $this->ingredients = new ArrayCollection();
        $this->userRequests = new ArrayCollection();
        $this->notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        $slugify = new Slugify();
        $slug = $slugify->slugify($this->title);
        $this->setSlug($slug);      

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

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

    public function getOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): self
    {
        $this->online = $online;

        return $this;
    }

    public function getValidate(): ?bool
    {
        return $this->validate;
    }

    public function setValidate(bool $validate): self
    {
        $this->validate = $validate;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    /**
     * @return Collection|Diet[]
     */
    public function getDiet(): Collection
    {
        return $this->diet;
    }

    public function addDiet(Diet $diet): self
    {
        if (!$this->diet->contains($diet)) {
            $this->diet[] = $diet;
        }

        return $this;
    }

    public function removeDiet(Diet $diet): self
    {
        if ($this->diet->contains($diet)) {
            $this->diet->removeElement($diet);
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): self
    {
        $this->note = $note;

        return $this;
    }


    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->setRecette($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredients->contains($ingredient)) {
            $this->ingredients->removeElement($ingredient);
            // set the owning side to null (unless already changed)
            if ($ingredient->getRecette() === $this) {
                $ingredient->setRecette(null);
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
            $userRequest->setRecette($this);
        }

        return $this;
    }

    public function removeUserRequest(UserRequest $userRequest): self
    {
        if ($this->userRequests->contains($userRequest)) {
            $this->userRequests->removeElement($userRequest);
            // set the owning side to null (unless already changed)
            if ($userRequest->getRecette() === $this) {
                $userRequest->setRecette(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->addRecette($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            $note->removeRecette($this);
        }

        return $this;
    }



    // Sert à convertir les entités en string IMPERATIF pour EasyAdminBundle
    public function __toString()
    {
        return $this->title;
    }


}
