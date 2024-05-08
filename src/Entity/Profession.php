<?php

namespace App\Entity;

use App\Repository\ProfessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfessionRepository::class)]
class Profession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NPE = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\OneToMany(targetEntity: RDV::class, mappedBy: 'profession')]
    private Collection $rdvsP;

    #[ORM\OneToOne(mappedBy: 'profession', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    

    public function __construct()
    {
        $this->rdvsP = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNPE(): ?string
    {
        return $this->NPE;
    }

    public function setNPE(string $NPE): static
    {
        $this->NPE = $NPE;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return Collection<int, RDV>
     */
    public function getRdvsP(): Collection
    {
        return $this->rdvsP;
    }

    public function addRdvsP(RDV $rdvsP): static
    {
        if (!$this->rdvsP->contains($rdvsP)) {
            $this->rdvsP->add($rdvsP);
            $rdvsP->setProfession($this);
        }

        return $this;
    }

    public function removeRdvsP(RDV $rdvsP): static
    {
        if ($this->rdvsP->removeElement($rdvsP)) {
            // set the owning side to null (unless already changed)
            if ($rdvsP->getProfession() === $this) {
                $rdvsP->setProfession(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setProfession(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getProfession() !== $this) {
            $user->setProfession($this);
        }

        $this->user = $user;

        return $this;
    }

}
