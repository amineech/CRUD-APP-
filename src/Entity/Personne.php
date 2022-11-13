<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
class Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Champ obligatoire")]
    #[Assert\Length(max: 30, maxMessage: "30 caractères maximum")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Champ obligatoire")]
    #[Assert\Length(max: 30, maxMessage: "30 caractères maximum")]
    private ?string $prenom = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateNaissance = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 100, maxMessage: "100 caractères maximum")]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 20, maxMessage: "20 caractères maximum")]
    private ?string $ville = null;

    #[ORM\Column]
    #[Assert\Length(max: 5, min: 5, exactMessage: "5 chiffres requis")]
    private ?int $codePostale = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    // constructeur par defaut
    public function __construct()
    {
        // date de creation d'enregistrement 
        $this->setCreatedAt(new DateTimeImmutable());
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeImmutable
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeImmutable $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

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

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodePostale(): ?int
    {
        return $this->codePostale;
    }

    public function setCodePostale(int $codePostale): self
    {
        $this->codePostale = $codePostale;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
