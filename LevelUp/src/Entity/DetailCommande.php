<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $idElemc;

    /**
     * @var int
     *
     * @ORM\Column(name="Quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var \Commande
     *
     * @ORM\ManyToOne(targetEntity="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_commande", referencedColumnName="id_commande")
     * })
     */
    private $idCommande;

    /**
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id_produit")
     * })
     */
    private $id;

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

    public function getIdCommande(): ?Commande
    {
        return $this->idCommande;
    }

    public function setIdCommande(?Commande $idCommande): self
    {
        $this->idCommande = $idCommande;

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
