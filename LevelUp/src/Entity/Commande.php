<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="fk_user1", columns={"id_user"})})
 * @ORM\Entity
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_commande", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("post:read")
     */
    private $idCommande;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_livraison", type="float", precision=10, scale=0, nullable=false)
     * @Groups("post:read")
     */
    private $prixLivraison;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_commande", type="date", nullable=false)
     * @Groups("post:read")
     */
    private $dateCommande;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_produits", type="float", precision=10, scale=0, nullable=false)
     * @Groups("post:read")
     */
    private $prixProduits;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_total", type="float", precision=10, scale=0, nullable=false)
     * @Groups("post:read")
     */
    private $prixTotal;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @Groups("post:read")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $idUser;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float",precision=14, scale=0, nullable=false)
     * @Groups("post:read")
     */
    private $latitude;

     /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float",precision=14, scale=0, nullable=false)
     * @Groups("post:read")
     */
    private $longitude;

        /**
     * @var string
     *
     * @ORM\Column(name="mode", type="string", length=255, nullable=false)
     * @Groups("post:read")
     */
    private $mode;

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getIdCommande(): ?int
    {
        return $this->idCommande;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function getPrixLivraison(): ?float
    {
        return $this->prixLivraison;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
    
    public function setPrixLivraison(float $prixLivraison): self
    {
        $this->prixLivraison = $prixLivraison;

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getPrixProduits(): ?float
    {
        return $this->prixProduits;
    }

    public function setPrixProduits(float $prixProduits): self
    {
        $this->prixProduits = $prixProduits;

        return $this;
    }

    public function getPrixTotal(): ?float
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(float $prixTotal): self
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function __toString()
    {
    return (string) $this->getPrixProduits();
    }

}
