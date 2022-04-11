<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUser;

    /**
     * @var string
     *
     * @Assert\NotBlank(message=" Email doit etre non vide")
     * @Assert\Email(message=" L'adresse email n'est pas valide")
     * @ORM\Column(name="email", type="string", length=254, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\NotBlank(message=" Password doit etre non vide")
     * @ORM\Column(name="password", type="string", length=254, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=254, nullable=false)
     */
    private $role;

    /**
     * @var string
     *
     * @Assert\NotBlank(message=" Le nom doit etre non vide")
     * @ORM\Column(name="nom", type="string", length=254, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @Assert\NotBlank(message=" Le prenom doit etre non vide")
     * @ORM\Column(name="prenom", type="string", length=254, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @Assert\NotBlank(message=" L'adresse doit etre non vide")
     * @ORM\Column(name="adresse", type="string", length=254, nullable=false)
     */
    private $adresse;

    /**
     * @var string
     *
     * @Assert\NotBlank(message=" le numero de telephone doit etre non vide")
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage=" Entrer un numero de 8 chiffres",
     *      maxMessage=" Entrer un numero de 8 chiffres"
     *
     *     )
     * @Assert\Regex(
     *     pattern="/^[0-9\-\_]+$/",
     *     message=" le numero de telephone est non valid"
     * )
     * @ORM\Column(name="tel", type="string", length=254, nullable=false)
     */
    private $tel;

    /**
     * @var \DateTime|null
     * @Assert\LessThan("-6 years",message="Votre age doit etre superieur a 6ans"  )
     * @Assert\NotBlank(message="La date doit etre non vide")
     * @ORM\Column(name="dns", type="date", nullable=true)
     */
    private $dns;

    /**
     * @var bool|null
     *
     * 
     * @ORM\Column(name="locked", type="boolean", nullable=true)
     */
    private $locked = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="tentative", type="integer", nullable=false)
     */
    private $tentative = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="limite", type="datetime", nullable=true)
     */
    private $limite;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=254, nullable=true)
     */
    private $image;

     /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @var string
     */
    private $sexe;

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
    public function getSexe()
    {
        return $this->sexe;
    }
    
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;
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
    public function setImageFile($image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }
    public function getImageFile()
    {
        return $this->imageFile;
    }

}
