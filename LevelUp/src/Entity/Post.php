<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vangrg\ProfanityBundle\Validator\Constraints as ProfanityAssert;
use Symfony\Component\Serializer\Annotation\Groups;



/**
 * Post
 *
 * @ORM\Table(name="post", indexes={@ORM\Index(name="fkpost_user", columns={"id_user"})})
 * @ORM\Entity
 */
class Post
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups ("post:read")

     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message= "Titre obligatoire" )
     * @ProfanityAssert\ProfanityCheck
     * @Assert\Length(min=7, minMessage="Le titre doit faire au moins {{ limit }} caractères.")
     * @Groups ("post:read")

     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=65535, nullable=false)
     * @Assert\NotBlank(message= " Description obligatoire" )
     * @ProfanityAssert\ProfanityCheck
     * @Assert\Length(min=10,max=100,minMessage="La Description doit faire au moins {{ limit }} caractères.")
     * @Groups ("post:read")

     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datep", type="date", nullable=false)
     * @Groups ("post:read")

     */
    private $datep;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     *

     * })
     * @Groups ("post:read")
     */
    private $idUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDatep(): ?\DateTimeInterface
    {
        return $this->datep=new \DateTime('now');
    }

    public function setDatep(\DateTimeInterface $datep): self
    {
        $this->datep = $date=new \DateTime('now');

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

    protected $captchaCode;

    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }
}
