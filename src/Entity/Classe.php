<?php

namespace App\Entity;

use App\Entity\Prof;
use Assert\NotBlank;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClasseRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClasseRepository::class)]
class Classe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getEleves", "getClasses", "getProfs"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getEleves", "getClasses", "getProfs"])]
    #[Assert\NotBlank(message: "Le nom de la classe est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le titre doit faire au moins {{ limit }} caractères", maxMessage: "Le titre ne peut pas faire plus de {{ limit }} caractères")]

    private ?string $nom = null;

    #[ORM\ManyToOne(targetEntity:'Prof'::class,inversedBy: 'Classe')]
    #[ORM\JoinColumn(nullable:true, onDelete:'SET NULL')]
    #[Groups(["getClasses"])]
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
