<?php

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 * @ORM\Table(name="user")
 * @ORM\Entity
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, TwoFactorInterface
{
    /**
     * @var int
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     * @ORM\Id
     * @Groups ("productsgroup")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idUser;

    /**
     * @var string
     * @Assert\NotBlank(message=" Email doit etre non vide")
     * @Assert\Email(message=" L'adresse email n'est pas valide")
     * @Groups ("productsgroup")
     * @ORM\Column(name="email", type="string", length=254, unique=true,nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups ("productsgroup")
     */
    private $roles = [];

    /**
     * @var string|null The hashed password
     * @Assert\NotBlank(message=" Password doit etre non vide")
     * @Assert\EqualTo(propertyPath="repeatPassword", message="mot de passe non identique")
     * @Assert\Length(
     *      min = 6,
     *      minMessage="Votre mot de passe doit comporter au moins 6 caractères",
     *     )
     * @Groups ("productsgroup")
     * @ORM\Column(name="password", type="string", length=254, nullable=true)
     */
    private $password;
   
    /**
     *@Assert\EqualTo(propertyPath="password", message="mot de passe non identique")
     */
    private $repeatPassword;
    /**
     * @var string
     * @ORM\Column(name="role", type="string", length=254, nullable=true)
     * @Groups ("productsgroup")
     */
    private $role;

    /**
     * @var string
     * @Assert\NotBlank(message=" Le nom doit etre non vide")
     * @ORM\Column(name="nom", type="string", length=254, nullable=true)
     * @Groups ("productsgroup")
     */
    private $nom;

    /**
     * @var string
     * @Assert\NotBlank(message=" Le prenom doit etre non vide")
     * @ORM\Column(name="prenom", type="string", length=254, nullable=true)
     * @Groups ("productsgroup")
     */
    private $prenom;

    /**
     * @var string
     * @Assert\NotBlank(message=" L'adresse doit etre non vide")
     * @ORM\Column(name="adresse", type="string", length=254, nullable=true)
     * @Groups ("productsgroup")
     */
    private $adresse;

    /**
     * @var string
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
     * @ORM\Column(name="tel", type="string", length=254, nullable=true)
     * @Groups ("productsgroup")
     */
    private $tel;

    /**
     * @var \DateTime|null
     * @Assert\LessThan("-6 years",message="Votre age doit etre superieur a 6ans"  )
     * @Assert\NotBlank(message="La date doit etre non vide")
     * @ORM\Column(name="dns", type="date", nullable=true)
     * @Groups ("productsgroup")
     */
    private $dns;

    /**
     * @var bool|null
     * @ORM\Column(name="locked", type="boolean", nullable=true)
     */
    private $locked = '0';

    /**
     * @var int
     * @ORM\Column(name="tentative", type="integer", nullable=false)
     */
    private $tentative = '0';

    /**
     * @var \DateTime|null
     * @ORM\Column(name="limite", type="datetime", nullable=true)
     */
    private $limite;

    /**
     * @var string|null
     * @ORM\Column(name="image", type="string", length=254, nullable=true)
     * @Groups ("productsgroup")
     */
    private $image;

     /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     * @var File
     * @Assert\NotBlank(message="il faut selectionner une image")
     */
    private $imageFile;

    /**
     * @var string
     */
    private $sexe;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $activation_token;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $reset_token;

    /**
     * @var boolean
     */
    private $isVerified = false;

    /**
     * @var string
     *
     * @ORM\Column(name="authCode", type="string", length=255, nullable=true)
     */
    private $authCode;

    
    

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

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

    public function getRepeatPassword()
    {
        return $this->repeatPassword;
    }
    
    public function setRepeatPassword($repeatPassword)
    {
        $this->repeatPassword = $repeatPassword;
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

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }
    

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        //$this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }
    public function isEmailAuthEnabled(): bool
    {
        return true; // This can be a persisted field to switch email code authentication on/off
    }

    public function getEmailAuthRecipient(): string
    {
        return $this->email;
    }

    public function getEmailAuthCode(): string
    {
        if (null === $this->authCode) {
            throw new \LogicException('The email authentication code was not set');
        }

        return $this->authCode;
    }

    public function setEmailAuthCode(string $authCode): void
    {
        $this->authCode = $authCode;
    }
    
}
