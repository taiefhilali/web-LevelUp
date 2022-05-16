<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Facture
 *
 * @ORM\Table(name="facture", indexes={@ORM\Index(name="fk_facture", columns={"id_user"})})
 * @ORM\Entity
 */
class Facture
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_facture", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups ("post:read")
     */
    private $idFacture;

    /**
     * @var \DateType|date
     * @ORM\Column(name="date", type="date", nullable=false)
     * @Groups ("post:read")
     */
    private $date;

    /**
     * @var string
     * @Assert\NotBlank(message=" prixTotal doit etre non vide")
     * @ORM\Column(name="prix_total", type="string", length=255, nullable=false)
     * @Groups ("post:read")
     */
    private $prixTotal;

    /**
     * @var \User
     * @Assert\NotBlank(message=" User doit etre non vide")
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     * @Groups ("post:read")
     */
    private $idUser;

    public function getIdFacture(): ?int
    {
        return $this->idFacture;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPrixTotal(): ?string
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(string $prixTotal): self
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


}
