<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * DetailCommande
 *
 * @ORM\Table(name="detail_commande", uniqueConstraints={@ORM\UniqueConstraint(name="id", columns={"id", "id_commande"})}, indexes={@ORM\Index(name="fk_commande1", columns={"id_commande"}), @ORM\Index(name="IDX_98344FA6BF396750", columns={"id"})})
 * @ORM\Entity
 */
class DetailCommande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_elemC", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("post:read")
     */
    private $idElemc;

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
     * @var \Commande
     *
     * @ORM\ManyToOne(targetEntity="Commande")
     * @Groups("post:read")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_commande", referencedColumnName="id_commande")
     * })
     */
    private $idCommande;

    public function getIdElemc(): ?int
    {
        return $this->idElemc;
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

    public function getIdCommande(): ?Commande
    {
        return $this->idCommande;
    }

    public function setIdCommande(?Commande $idCommande): self
    {
        $this->idCommande = $idCommande;

        return $this;
    }


}
