<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;



/**
 * Post
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
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message= "Titre obligatoire" )
     * @Assert\Length(min=7, minMessage="Le titre doit faire au moins {{ limit }} caractères.")


     */

    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=65535, nullable=false)
     * @Assert\NotBlank(message= " Description obligatoire" )
     * @Assert\Length(min=10,minMessage="La Description doit faire au moins {{ limit }} caractères.")




     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datep", type="date", nullable=false)


     */
    private $datep;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")

     * })
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


}
