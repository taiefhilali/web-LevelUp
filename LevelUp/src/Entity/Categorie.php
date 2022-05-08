<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity
 * @UniqueEntity(fields={"nomCategorie"}, message="La catégorie {{ value }} est déja existante! Veuillez choisir un autre nom.")
 */
class Categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_categorie", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups ("productsgroup")
     */
    private $idCategorie;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Le nom de la catégorie est obligatoire! Veuillez remplir tout les champs!")
     * @ORM\Column(name="nom_categorie", type="string", length=254, nullable=false)
     * @Groups ("productsgroup")
     */
    private $nomCategorie;

    public function getIdCategorie(): ?int
    {
        return $this->idCategorie;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nomCategorie;
    }

    public function setNomCategorie(string $nomCategorie): self
    {
        $this->nomCategorie = $nomCategorie;

        return $this;
    }
 public function __toString(){
        return $this->getNomCategorie();

    }


}
