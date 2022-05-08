<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * PanierElem
 *
 * @ORM\Table(name="panier_elem", uniqueConstraints={@ORM\UniqueConstraint(name="id_panier", columns={"id_panier", "id"}), @ORM\UniqueConstraint(name="id_panier_2", columns={"id_panier", "id"})}, indexes={@ORM\Index(name="fk_produit1", columns={"id"}), @ORM\Index(name="IDX_B31E4D172FBB81F", columns={"id_panier"})})
 * @ORM\Entity
 */
class PanierElem
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_elem", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("post:read")
     */
    private $idElem;

    /**
     * @var int
     *
     * @ORM\Column(name="Quantite", type="integer", nullable=false)
     * @Groups("post:read")
     */
    private $quantite;

    /**
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @Groups("post:read")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id_produit")
     * })
     */
    private $id;

    /**
     * @var \Panier
     *
     * @ORM\ManyToOne(targetEntity="Panier")
     * @Groups("post:read")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_panier", referencedColumnName="id_panier")
     * })
     */
    private $idPanier;

    public function getIdElem(): ?int
    {
        return $this->idElem;
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

    public function getId(): ?Produit
    {
        return $this->id;
    }

    public function setId(?Produit $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getIdPanier(): ?Panier
    {
        return $this->idPanier;
    }

    public function setIdPanier(?Panier $idPanier): self
    {
        $this->idPanier = $idPanier;

        return $this;
    }


}
