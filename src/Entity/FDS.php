<?php

namespace App\Entity;

use App\Repository\FDSRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FDSRepository::class)]
class FDS
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $filePath = null;
 
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column (nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?int $version = null;

    #[ORM\Column(length: 255)]
    private ?string $chemicalName = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $practice = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $dangerWarnings = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $cautionaryAdvice = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(string $filePath): static
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt->format('Y-m-d');
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt->format('Y-m-d');
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(int $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function getChemicalName(): ?string
    {
        return $this->chemicalName;
    }

    public function setChemicalName(string $chemicalName): static
    {
        $this->chemicalName = $chemicalName;

        return $this;
    }

    public function getPractice(): ?string
    {
        return $this->practice;
    }

    public function setPractice(string $practice): static
    {
        $this->practice = $practice;

        return $this;
    }

    public function getDangerWarnings(): ?string
    {
        return $this->dangerWarnings;
    }

    public function setDangerWarnings(string $dangerWarnings): static
    {
        $this->dangerWarnings = $dangerWarnings;

        return $this;
    }

    public function getCautionaryAdvice(): ?string
    {
        return $this->cautionaryAdvice;
    }

    public function setCautionaryAdvice(?string $cautionaryAdvice): static
    {
        $this->cautionaryAdvice = $cautionaryAdvice;

        return $this;
    }
}
