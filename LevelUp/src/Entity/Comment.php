<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;



/**
 * Comment
 *
 * @ORM\Table(name="comment", indexes={@ORM\Index(name="fk_comment_post", columns={"id_post"})})
 * @ORM\Entity
 */
class Comment
{
    /**
     * @var int
     *
     * @ORM\Column(name="idc", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups ("post:read")
     */
    private $idc;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text", length=65535, nullable=false)
     * @Assert\NotBlank(message= "Reponse obligatoire" )
     * @Assert\Length(min=5, minMessage="La Reponse doit faire au moins {{ limit }} caractères.")
     * @Groups ("post:read")
     */
    private $contenu;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message= "Review obligatoire" )
     *  @Assert\Length(min=3, minMessage="Le Review doit faire au moins {{ limit }} caractères.")
     * @Groups ("post:read")
     */
    private $label;

    /**
     * @var int
     *
     * @ORM\Column(name="resp", type="integer", nullable=false)
     * @Assert\NotBlank(message= "Rate obligatoire" )
     * @Assert\LessThan(5)
     * @Assert\GreaterThan(0)
     * @Groups ("post:read")

     */
    private $resp;

    /**
     * @var \Post
     *
     * @ORM\ManyToOne(targetEntity="Post")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_post", referencedColumnName="id")
     * })
     * @Groups ("post:read")
     */
    private $idPost;

    public function getIdc(): ?int
    {
        return $this->idc;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getResp(): ?int
    {
        return $this->resp;
    }

    public function setResp(int $resp): self
    {
        $this->resp = $resp;

        return $this;
    }

    public function getIdPost(): ?Post
    {
        return $this->idPost;
    }

    public function setIdPost(?Post $idPost): self
    {
        $this->idPost = $idPost;

        return $this;
    }


}
