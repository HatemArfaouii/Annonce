<?php
// /src/Entity/User.php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity(fields="email", message="Email déjà pris")
 * @UniqueEntity(fields="username", message="Username déjà pris")
 * @Vich\Uploadable()

 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $statut;

    /**
     * @return mixed
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param mixed $statut
     */
    public function setStatut($statut): void
    {
        $this->statut = $statut;
    }

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
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
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     */
    private $username;


    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $addresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telmobile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telfixe;

    /**
     * @ORM\Column(type="integer")
     */
    private $codepost;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Annonce", mappedBy="userid", cascade={"remove"})
     */
    private $annonceid;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="usercommet", cascade={"remove"})
     */
    private $annoncecomment;


    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->annonceid = new ArrayCollection();
        $this->annoncecomment = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    // le ? signifie que cela peur aussi retourner null
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Retourne les rôles de l'user
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // Afin d'être sûr qu'un user a toujours au moins 1 rôle
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Retour le salt qui a servi à coder le mot de passe
     *
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        // See "Do you need to use a Salt?" at https://symfony.com/doc/current/cookbook/security/entity_provider.html
        // we're using bcrypt in security.yml to encode the password, so
        // the salt value is built-in and you don't have to generate one

        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        // Nous n'avons pas besoin de cette methode car nous n'utilions pas de plainPassword
        // Mais elle est obligatoire car comprise dans l'interface UserInterface
        // $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize([$this->id, $this->username, $this->password]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        [$this->id, $this->username, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }

    public function getAddresse(): ?string
    {
        return $this->addresse;
    }

    public function setAddresse(string $addresse): self
    {
        $this->addresse = $addresse;

        return $this;
    }

    public function getTelmobile(): ?string
    {
        return $this->telmobile;
    }

    public function setTelmobile(string $telmobile): self
    {
        $this->telmobile = $telmobile;

        return $this;
    }

    public function getTelfixe(): ?string
    {
        return $this->telfixe;
    }

    public function setTelfixe(string $telfixe): self
    {
        $this->telfixe = $telfixe;

        return $this;
    }

    public function getCodepost(): ?int
    {
        return $this->codepost;
    }

    public function setCodepost(int $codepost): self
    {
        $this->codepost = $codepost;

        return $this;
    }





    public function __toString()
    {
        return (string) $this->getId();
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonceid(): Collection
    {
        return $this->annonceid;
    }

    public function addAnnonceid(Annonce $annonceid): self
    {
        if (!$this->annonceid->contains($annonceid)) {
            $this->annonceid[] = $annonceid;
            $annonceid->setUserid($this);
        }

        return $this;
    }

    public function removeAnnonceid(Annonce $annonceid): self
    {
        if ($this->annonceid->contains($annonceid)) {
            $this->annonceid->removeElement($annonceid);
            // set the owning side to null (unless already changed)
            if ($annonceid->getUserid() === $this) {
                $annonceid->setUserid(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getAnnoncecomment(): Collection
    {
        return $this->annoncecomment;
    }

    public function addAnnoncecomment(Comment $annoncecomment): self
    {
        if (!$this->annoncecomment->contains($annoncecomment)) {
            $this->annoncecomment[] = $annoncecomment;
            $annoncecomment->setUsercommet($this);
        }

        return $this;
    }

    public function removeAnnoncecomment(Comment $annoncecomment): self
    {
        if ($this->annoncecomment->contains($annoncecomment)) {
            $this->annoncecomment->removeElement($annoncecomment);
            // set the owning side to null (unless already changed)
            if ($annoncecomment->getUsercommet() === $this) {
                $annoncecomment->setUsercommet(null);
            }
        }

        return $this;
    }
}
