<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="commentuser")
     */
    private $usercommet;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Annonce", inversedBy="comments")
     */
    private $annonceid;

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUsercommet(): ?User
    {
        return $this->usercommet;
    }

    public function setUsercommet(?User $usercommet): self
    {
        $this->usercommet = $usercommet;

        return $this;
    }



    public function __toString(){
        return (string) $this->getId();
    }

    public function getAnnonceid(): ?Annonce
    {
        return $this->annonceid;
    }

    public function setAnnonceid(?Annonce $annonceid): self
    {
        $this->annonceid = $annonceid;

        return $this;
    }
}
