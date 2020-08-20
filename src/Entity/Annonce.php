<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnonceRepository")
 * * @Vich\Uploadable()
 */
class Annonce
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $filename;
    /**
     * @var File|null
     * @Vich\UploadableField(mapping="user_image" , fileNameProperty="filename")
     */
    private $imageFile;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     */
    public function setFilename(?string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at): void
    {
        $this->updated_at = $updated_at;
    }


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     */
    private $verifannonce;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Subcategory", inversedBy="annonces")
     */
    private $subcategory;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }


    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getVerifannonce(): ?int
    {
        return $this->verifannonce;
    }

    public function setVerifannonce(int $verifannonce): self
    {
        $this->verifannonce = $verifannonce;

        return $this;
    }

    public function getSubcategory(): ?Subcategory
    {
        return $this->subcategory;
    }

    public function setSubcategory(?Subcategory $subcategory): self
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getTitre();
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }


    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename2;
    /**
     * @var File|null
     * @Vich\UploadableField(mapping="user_image" , fileNameProperty="filename2")
     */
    private $imageFile2;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at2;

    /**
     * @return string|null
     */
    public function getFilename2(): ?string
    {
        return $this->filename2;
    }

    /**
     * @param string|null $filename2
     */
    public function setFilename2(?string $filename2): void
    {
        $this->filename2 = $filename2;
    }

    /**
     * @return File|null
     */
    public function getImageFile2(): ?File
    {
        return $this->imageFile2;
    }

    /**
     * @param File|null $imageFile2
     * @throws \Exception
     */
    public function setImageFile2(?File $imageFile2): void
    {
        $this->imageFile2 = $imageFile2;
        if ($this->imageFile2 instanceof UploadedFile2) {
            $this->updated_at2 = new \DateTime('now');
        }
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt2()
    {
        return $this->updated_at2;
    }

    /**
     * @param mixed $updated_at2
     */
    public function setUpdatedAt2($updated_at2): void
    {
        $this->updated_at2 = $updated_at2;
    }




    //image file 3

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename3;
    /**
     * @var File|null
     * @Vich\UploadableField(mapping="user_image" , fileNameProperty="filename3")
     */
    private $imageFile3;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at3;

    /**
     * @return string|null
     */
    public function getFilename3(): ?string
    {
        return $this->filename3;
    }

    /**
     * @param string|null $filename3
     */
    public function setFilename3(?string $filename3): void
    {
        $this->filename3 = $filename3;
    }

    /**
     * @return File|null
     */
    public function getImageFile3(): ?File
    {
        return $this->imageFile3;
    }

    /**
     * @param File|null $imageFile3
     * @throws \Exception
     */
    public function setImageFile3(?File $imageFile3): void
    {
        $this->imageFile3 = $imageFile3;
        if ($this->imageFile3 instanceof UploadedFile3) {
            $this->updated_at3 = new \DateTime('now');
        }
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt3()
    {
        return $this->updated_at3;
    }

    /**
     * @param mixed $updated_at3
     */
    public function setUpdatedAt3($updated_at3): void
    {
        $this->updated_at3 = $updated_at3;
    }


    //image file 4

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename4;
    /**
     * @var File|null
     * @Vich\UploadableField(mapping="user_image" , fileNameProperty="filename4")
     */
    private $imageFile4;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at4;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="annonceid")
     */
    private $userid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateadd;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="annonceid", cascade={"remove"})
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }



    /**
     * @return string|null
     */
    public function getFilename4(): ?string
    {
        return $this->filename4;
    }

    /**
     * @param string|null $filename4
     */
    public function setFilename4(?string $filename4): void
    {
        $this->filename4 = $filename4;
    }

    /**
     * @return File|null
     */
    public function getImageFile4(): ?File
    {
        return $this->imageFile4;
    }

    /**
     * @param File|null $imageFile4
     * @throws \Exception
     */
    public function setImageFile4(?File $imageFile4): void
    {
        $this->imageFile4 = $imageFile4;
        if ($this->imageFile4 instanceof UploadedFile4) {
            $this->updated_at4 = new \DateTime('now');
        }
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt4()
    {
        return $this->updated_at4;
    }

    /**
     * @param mixed $updated_at4
     */
    public function setUpdatedAt4($updated_at4): void
    {
        $this->updated_at = $updated_at4;
    }

    public function getUserid(): ?User
    {
        return $this->userid;
    }

    public function setUserid(?User $userid): self
    {
        $this->userid = $userid;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDateadd(): ?\DateTimeInterface
    {
        return $this->dateadd = new \DateTime('now');
    }

    public function setDateadd(?\DateTimeInterface $dateadd): self
    {
        $this->dateadd = $dateadd;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAnnonceid($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAnnonceid() === $this) {
                $comment->setAnnonceid(null);
            }
        }

        return $this;
    }
}
