<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProfRepository::class)]
class Prof
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getEleves", "getProfs", "getClasses"])]
    
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getEleves", "getProfs", "getClasses"])]
    #[Assert\NotBlank(message: "Le nom du prof est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le titre doit faire au moins {{ limit }} caractères", maxMessage: "Le titre ne peut pas faire plus de {{ limit }} caractères")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getEleves", "getProfs", "getClasses"])]
    
    private ?string $prenom = null;

    #[ORM\OneToMany(mappedBy: 'prof', targetEntity: Classe::class)]
    #[Groups(["getEleves", "getProfs"])]
    private Collection $Classe;
    
    #[ORM\OneToMany(mappedBy: 'prof', targetEntity: Eleve::class)]
    #[Groups(["getProfs", "getClasses"])]
    
    private Collection $Eleve;
    
    

    public function __construct()
    {
        $this->Classe = new ArrayCollection();
        $this->Eleve = new ArrayCollection();
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

    /**
     * @return Collection<int, Classe>
     */
    public function getClasse(): Collection
    {
        return $this->Classe;
    }

    public function addClasse(Classe $classe): self
    {
        if (!$this->Classe->contains($classe)) {
            $this->Classe->add($classe);
            $classe->setProf($this);
        }

        return $this;
    }

    public function removeClasse(Classe $classe): self
    {
        if ($this->Classe->removeElement($classe)) {
            // set the owning side to null (unless already changed)
            if ($classe->getProf() === $this) {
                $classe->setProf(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Eleve>
     */
    public function getEleve(): Collection
    {
        return $this->Eleve;
    }

    public function addEleve(Eleve $eleve): self
    {
        if (!$this->Eleve->contains($eleve)) {
            $this->Eleve->add($eleve);
            $eleve->setProf($this);
        }

        return $this;
    }

    public function removeEleve(Eleve $eleve): self
    {
        if ($this->Eleve->removeElement($eleve)) {
            // set the owning side to null (unless already changed)
            if ($eleve->getProf() === $this) {
                $eleve->setProf(null);
            }
        }

        return $this;
    }
}
