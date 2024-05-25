<?php

namespace App\Entity;

use App\Repository\RDVRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RDVRepository::class)]
class RDV
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateRdv = null;

    #[ORM\ManyToOne(inversedBy: 'rdvs')]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(inversedBy: 'rdvsP')]
    private ?Profession $profession = null;

    #[ORM\OneToOne(inversedBy: 'rdvsT', cascade: ['persist', 'remove'])]
    private ?Traitements $traitements = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Prescription = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRdv(): ?\DateTimeInterface
    {
        return $this->DateRdv;
    }

    public function setDateRdv(\DateTimeInterface $DateRdv): static
    {
        $this->DateRdv = $DateRdv;

        return $this;
    }

    public function getPatient(): ?patient
    {
        return $this->patient;
    }

    public function setPatient(?patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getProfession(): ?profession
    {
        return $this->profession;
    }

    public function setProfession(?profession $profession): static
    {
        $this->profession = $profession;

        return $this;
    }

    public function getTraitements(): ?traitements
    {
        return $this->traitements;
    }

    public function setTraitements(?traitements $traitements): static
    {
        $this->traitements = $traitements;

        return $this;
    }

    public function getPrescription(): ?string
    {
        return $this->Prescription;
    }

    public function setPrescription(?string $Prescription): static
    {
        $this->Prescription = $Prescription;

        return $this;
    }
}
