<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rolename;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rolecode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRolename(): ?string
    {
        return $this->rolename;
    }

    public function setRolename(string $rolename): self
    {
        $this->rolename = $rolename;

        return $this;
    }

    public function getRolecode(): ?string
    {
        return $this->rolecode;
    }

    public function setRolecode(string $rolecode): self
    {
        $this->rolecode = $rolecode;

        return $this;
    }

    public function __toString(){
        return (string) $this->getId();
    }
}
