<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Stock
 *
 * @ORM\Table(name="stock")
 * @ORM\Entity
 */
class Stock
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message=" nom doit etre non vide")
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var int
     *
     * @Assert\NotBlank(message=" quantite doit etre non vide")
     * @Assert\Length(
     *      max = 4,
     *      maxMessage=" Entrer la quantité au maximum 4 caracteres"
     *
     *     )
     * @ORM\Column(name="quantite", type="integer",)
     */
    private $quantite;

    /**
     * @var string
     * @Assert\NotBlank(message=" etat doit etre non vide")
     * @ORM\Column(name="etat", type="string", length=255)
     */
    private $etat;

    /**
     * @var \Produit
     *
     * @Assert\NotBlank(message=" user doit etre non vide")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id_produit")
     * })
     */
   // private $latitude;

   // /**
     //* @var float
     //*
     //* @ORM\Column(name="longitude", type="float",precision=14, scale=0, nullable=false)
     //*/
    //private $longitude;

    ///**
    // * @var string
     //*
     //* @ORM\Column(name="mode", type="string", length=255, nullable=false)
     //*/
    private $id;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }



    public function getId(): ?Produit
    {
        return $this->id;
    }

    public function setId(?Produit $id): self
    {
        $this->id = $id;

        return $this;
    }


}
