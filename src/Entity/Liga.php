<?php

namespace App\Entity;

use App\Repository\LigaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LigaRepository::class)
 */
class Liga
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $deporte;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre_liga;

    /**
     * @ORM\ManyToMany(targetEntity=equipo::class, inversedBy="ligas")
     */
    private $equipos;


    public function __construct()
    {
        $this->equipos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeporte(): ?string
    {
        return $this->deporte;
    }

    public function setDeporte(string $deporte): self
    {
        $this->deporte = $deporte;

        return $this;
    }

    public function getNombreLiga(): ?string
    {
        return $this->nombre_liga;
    }

    public function setNombreLiga(string $nombre_liga): self
    {
        $this->nombre_liga = $nombre_liga;

        return $this;
    }

    /**
     * @return Collection|equipo[]
     */
    public function getEquipos(): Collection
    {
        return $this->equipos;
    }

    public function addEquipo(equipo $equipo): self
    {
        if (!$this->equipos->contains($equipo)) {
            $this->equipos[] = $equipo;
        }

        return $this;
    }

    public function removeEquipo(equipo $equipo): self
    {
        $this->equipos->removeElement($equipo);

        return $this;
    }

   
}
