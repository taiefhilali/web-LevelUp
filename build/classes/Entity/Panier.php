<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Panier
 *
 * @ORM\Table(name="panier", uniqueConstraints={@ORM\UniqueConstraint(name="id_user", columns={"id_user"}), @ORM\UniqueConstraint(name="id_panier", columns={"id_panier", "id_user"})})
 * @ORM\Entity
 */
class Panier
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_panier", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPanier;

    /**
     * @var \Client
     *
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $idUser;

    public function getIdPanier(): ?int
    {
        return $this->idPanier;
    }

    public function getIdUser(): ?Client
    {
        return $this->idUser;
    }

    public function setIdUser(?Client $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }


}
