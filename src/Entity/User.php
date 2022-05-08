<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("post:read")
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=254, nullable=false)
     * @Groups("post:read")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=254, nullable=false)
     * @Groups("post:read")
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=254, nullable=false)
     * @Groups("post:read")
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=254, nullable=false)
     * @Groups("post:read")
     */
    private $nom;

    /**
     * @var string
     *@Groups("post:read")
     * @ORM\Column(name="prenom", type="string", length=254, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=254, nullable=false)
     * @Groups("post:read")
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=254, nullable=false)
     * @Groups("post:read")
     */
    private $tel;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="dns", type="date", nullable=true)
     * 
     */
    private $dns;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="locked", type="boolean", nullable=true)
     * 
     */
    private $locked = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="tentative", type="integer", nullable=false)
     * 
     */
    private $tentative = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="limite", type="datetime", nullable=true)
     * 
     */
    private $limite;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=254, nullable=true, options={"default"=""""})
     * @Groups("post:read")
     */
    private $image = '""';

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getDns(): ?\DateTimeInterface
    {
        return $this->dns;
    }

    public function setDns(?\DateTimeInterface $dns): self
    {
        $this->dns = $dns;

        return $this;
    }

    public function getLocked(): ?bool
    {
        return $this->locked;
    }

    public function setLocked(?bool $locked): self
    {
        $this->locked = $locked;

        return $this;
    }

    public function getTentative(): ?int
    {
        return $this->tentative;
    }

    public function setTentative(int $tentative): self
    {
        $this->tentative = $tentative;

        return $this;
    }

    public function getLimite(): ?\DateTimeInterface
    {
        return $this->limite;
    }

    public function setLimite(?\DateTimeInterface $limite): self
    {
        $this->limite = $limite;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }


}
