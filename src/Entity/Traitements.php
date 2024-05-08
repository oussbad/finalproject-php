<?php

namespace App\Entity;

use App\Repository\TraitementsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TraitementsRepository::class)]
class Traitements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToOne(mappedBy: 'traitements', cascade: ['persist', 'remove'])]
    private ?RDV $rdvsT = null;

    #[ORM\ManyToOne(inversedBy: 'traitments')]
    private ?Patient $trait = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRdvsT(): ?RDV
    {
        return $this->rdvsT;
    }

    public function setRdvsT(?RDV $rdvsT): static
    {
        // unset the owning side of the relation if necessary
        if ($rdvsT === null && $this->rdvsT !== null) {
            $this->rdvsT->setTraitements(null);
        }

        // set the owning side of the relation if necessary
        if ($rdvsT !== null && $rdvsT->getTraitements() !== $this) {
            $rdvsT->setTraitements($this);
        }

        $this->rdvsT = $rdvsT;

        return $this;
    }

    public function getTrait(): ?Patient
    {
        return $this->trait;
    }

    public function setTrait(?Patient $trait): static
    {
        $this->trait = $trait;

        return $this;
    }
}
