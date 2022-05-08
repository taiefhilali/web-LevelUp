<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="fk_rec_user", columns={"id_user"}), @ORM\Index(name="fk_rec_livraison", columns={"id_livraison"})})
 * @ORM\Entity
 */
class Reclamation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_reclamation", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("reclamations")
     */
    private $idReclamation;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=254, nullable=false)
     * @Groups("reclamations")
     */
    private $description;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="WARN", type="boolean", nullable=true)
     * @Groups("reclamations")
     */
    private $warn;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     * @Groups("reclamations")

     */
    private $idUser;

    /**
     * @var \Livraison
     *
     * @ORM\ManyToOne(targetEntity="Livraison")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_livraison", referencedColumnName="id_livraison")
     * })
     * @Groups("reclamations")

     */
    private $idLivraison;

    public function getIdReclamation(): ?int
    {
        return $this->idReclamation;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getWarn(): ?bool
    {
        return $this->warn;
    }

    public function setWarn(?bool $warn): self
    {
        $this->warn = $warn;

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

    public function getIdLivraison(): ?Livraison
    {
        return $this->idLivraison;
    }

    public function setIdLivraison(?Livraison $idLivraison): self
    {
        $this->idLivraison = $idLivraison;

        return $this;
    }


}
