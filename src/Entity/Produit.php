<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * Produit
 *
 * @ORM\Table(name="produit", indexes={@ORM\Index(name="fk_CategorieProduit", columns={"id_categorie"}), @ORM\Index(name="fk_idFournisseur", columns={"id_user"})})
 * @ORM\Entity
 * @Vich\Uploadable
 * @UniqueEntity(fields={"nom"}, message="⚠ Le nom du produit {{ value }} est déja existant! Veuillez choisir un autre nom.")
 * @UniqueEntity(fields={"reference"}, message="⚠ La référence {{ value }} est déja existante! Veuillez choisir une autre réference.")

 */
class Produit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_produit", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups ("productsgroup")
     */
    private $idProduit;

    /**
     * @var string
     * @Assert\Length(
     *      min = 6,
     *      max = 40,
     *      minMessage = "La longueur du nom doit être supérieur à 6 et inférieur à 40!",
     *      maxMessage =" doit etre <=40" )
     * @Assert\NotBlank(message="⚠ Le champ nom est obligatoire!")
     * @ORM\Column(name="nom", type="string", length=254, nullable=false)
     * @Groups ("productsgroup")
     */

    private $nom;

    /**
     * @var string
     * @Assert\NotBlank(message="⚠ La référence est obligatoire!")
     * @Assert\Length(
     *      min = 2,
     *      max = 20,
     *      minMessage = "La longueur du nom doit être supérieur à 2 et inférieur à 20!",
     *      maxMessage =" doit etre <=20" )
     * @ORM\Column(name="reference", type="string", length=254, nullable=false)
     * @Groups ("productsgroup")
     */
//    add a regex pattern constraint
    private $reference;

    /**
     * @var float
     * @Assert\GreaterThan (0)
     * @Assert\NotBlank(message="⚠ Le prix est obligatoire!")
     * @Assert\Positive(message="⚠ Le prix doit être positive!")
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     * @Groups ("productsgroup")
     */
    private $prix;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="⚠ La description est obligatoire!")
     * @Assert\Length(
     *      min = 5,
     *      max = 100,
     *      minMessage = "⚠ La longueur de la déscription doit être supérieur à 5",
     *      maxMessage ="doit etre <=500" )
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     * @Groups ("productsgroup")
     */
    private $description;

    /**
     * @var float
     *
     * @Assert\NotBlank(message="⚠ Veuillez remplir le champ promotion!")
     * @Assert\GreaterThan (-1, message="⚠ La promotion doit être positive!")
     * @ORM\Column(name="promotion", type="float", precision=10, scale=0, nullable=false)
     * @Groups ("productsgroup")
     */
    private $promotion;

    /**
     * @var string
     * @ORM\Column(name="image", type="string", length=254, nullable=false)
     * @Groups ("productsgroup")
     */
    private $image;
//    Image file attribute (bundle vich)
    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @var float
     *
     * @ORM\Column(name="prix_final", type="float", precision=10, scale=0, nullable=false)
     * @Groups ("productsgroup")
     */
    private $prixFinal = '0';

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     * @Groups ("productsgroup")
     */
    private $idUser;

    /**
     * @var \Categorie
     *
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie", referencedColumnName="id_categorie")
     * })
     * @Groups ("productsgroup")
     */
    private $idCategorie;

    public function getIdProduit(): ?int
    {
        return $this->idProduit;
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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
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

    public function getPromotion(): ?float
    {
        return $this->promotion;
    }

    public function setPromotion(float $promotion): self
    {
        $this->promotion = $promotion;

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

    public function getPrixFinal(): ?float
    {
        return $this->prixFinal;
    }

    public function setPrixFinal(float $prixFinal): self
    {
        $this->prixFinal =$prixFinal;

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

    public function getIdCategorie(): ?Categorie
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(?Categorie $idCategorie): self
    {
        $this->idCategorie = $idCategorie;

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
