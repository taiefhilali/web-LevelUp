<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reply
 *
 * @ORM\Table(name="reply", indexes={@ORM\Index(name="fk_comment_reply", columns={"idc"}), @ORM\Index(name="fk_user_reply", columns={"id_user"})})
 * @ORM\Entity(
 */
class Reply
{
    /**
     * @var int
     *
     * @ORM\Column(name="idr", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idr;

    /**
     * @var string
     *
     * @ORM\Column(name="replycnt", type="string", length=255, nullable=false)
     */
    private $replycnt;

    /**
     * @var \Comment
     *
     * @ORM\ManyToOne(targetEntity="Comment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idc", referencedColumnName="idc")
     * })
     */
    private $idc;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $idUser;

    public function getIdr(): ?int
    {
        return $this->idr;
    }

    public function getReplycnt(): ?string
    {
        return $this->replycnt;
    }

    public function setReplycnt(string $replycnt): self
    {
        $this->replycnt = $replycnt;

        return $this;
    }

    public function getIdc(): ?Comment
    {
        return $this->idc;
    }

    public function setIdc(?Comment $idc): self
    {
        $this->idc = $idc;

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
