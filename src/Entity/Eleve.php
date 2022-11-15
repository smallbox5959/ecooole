<?php

namespace App\Entity;

use App\Entity\Prof;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EleveRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EleveRepository::class)]
class Eleve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
   
    #[ORM\Column]
    #[Groups(["getEleves", "getProfs", "getClasses"])]
    
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getEleves", "getProfs", "getClasses"])]
    #[Assert\NotBlank(message: "Le nom de la classe est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le nom doit faire au moins {{ limit }} caractères", maxMessage: "Le nom ne peut pas faire plus de {{ limit }} caractères")]
    private ?string $nom = null;

    #[ORM\Column]
    #[Groups(["getEleves", "getProfs", "getClasses"])]
    
    private ?float $moyenne = null;

    #[ORM\ManyToOne(targetEntity:'Prof'::class, inversedBy: 'Eleve')]
    #[ORM\JoinColumn(nullable:true, onDelete:'SET NULL')]
    #[Groups(["getEleves"])]
    private ?Prof $prof = null;

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

    public function getMoyenne(): ?float
    {
        return $this->moyenne;
    }

    public function setMoyenne(float $moyenne): self
    {
        $this->moyenne = $moyenne;

        return $this;
    }

    public function getProf(): ?Prof
    {
        return $this->prof;
    }

    public function setProf(?Prof $prof): self
    {
        $this->prof = $prof;

        return $this;
    }
}
