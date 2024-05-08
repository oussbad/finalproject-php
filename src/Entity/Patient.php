<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $cin = null;

    #[ORM\OneToMany(targetEntity: RDV::class, mappedBy: 'patient', cascade: ['remove'])]
    private Collection $rdvs;

    #[ORM\OneToMany(targetEntity: Traitements::class, mappedBy: 'trait')]
    private Collection $traitments;

    #[ORM\OneToOne(mappedBy: 'patient', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    

    public function __construct()
    {
        $this->rdvs = new ArrayCollection();
        $this->traitments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    /**
     * @return Collection<int, RDV>
     */
    public function getRdvs(): Collection
    {
        return $this->rdvs;
    }

    public function addRdv(RDV $rdv): static
    {
        if (!$this->rdvs->contains($rdv)) {
            $this->rdvs->add($rdv);
            $rdv->setPatient($this);
        }

        return $this;
    }

    public function removeRdv(RDV $rdv): static
    {
        if ($this->rdvs->removeElement($rdv)) {
            // set the owning side to null (unless already changed)
            if ($rdv->getPatient() === $this) {
                $rdv->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, traitements>
     */
    public function getTraitments(): Collection
    {
        return $this->traitments;
    }

    public function addTraitment(traitements $traitment): static
    {
        if (!$this->traitments->contains($traitment)) {
            $this->traitments->add($traitment);
            $traitment->setTrait($this);
        }

        return $this;
    }

    public function removeTraitment(traitements $traitment): static
    {
        if ($this->traitments->removeElement($traitment)) {
            // set the owning side to null (unless already changed)
            if ($traitment->getTrait() === $this) {
                $traitment->setTrait(null);
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
            $this->user->setPatient(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getPatient() !== $this) {
            $user->setPatient($this);
        }

        $this->user = $user;

        return $this;
    }

   
}
