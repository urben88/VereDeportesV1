<?php

namespace App\Entity;

use App\Repository\EquipoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipoRepository::class)
 */
class Equipo
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
     * @ORM\Column(type="boolean")
     */
    private $capitan;

    /**
     * @ORM\OneToMany(targetEntity=Solicita::class, mappedBy="id_equipo")
     */
    private $solicitas;

    /**
     * @ORM\ManyToMany(targetEntity=Liga::class, mappedBy="equipos")
     */
    private $equipos;

    /**
     * @ORM\ManyToMany(targetEntity=Liga::class, mappedBy="equipos")
     */
    private $ligas;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    public function __construct()
    {
        $this->solicitas = new ArrayCollection();
        $this->equipos = new ArrayCollection();
        $this->ligas = new ArrayCollection();
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

    public function getCapitan(): ?bool
    {
        return $this->capitan;
    }

    public function setCapitan(bool $capitan): self
    {
        $this->capitan = $capitan;

        return $this;
    }

    /**
     * @return Collection|Solicita[]
     */
    public function getSolicitas(): Collection
    {
        return $this->solicitas;
    }

    public function addSolicita(Solicita $solicita): self
    {
        if (!$this->solicitas->contains($solicita)) {
            $this->solicitas[] = $solicita;
            $solicita->setIdEquipo($this);
        }

        return $this;
    }

    public function removeSolicita(Solicita $solicita): self
    {
        if ($this->solicitas->removeElement($solicita)) {
            // set the owning side to null (unless already changed)
            if ($solicita->getIdEquipo() === $this) {
                $solicita->setIdEquipo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Liga[]
     */
    public function getEquipos(): Collection
    {
        return $this->equipos;
    }

    public function addEquipo(Liga $equipo): self
    {
        if (!$this->equipos->contains($equipo)) {
            $this->equipos[] = $equipo;
            $equipo->addEquipo($this);
        }

        return $this;
    }

    public function removeEquipo(Liga $equipo): self
    {
        if ($this->equipos->removeElement($equipo)) {
            $equipo->removeEquipo($this);
        }

        return $this;
    }

    /**
     * @return Collection|Liga[]
     */
    public function getLigas(): Collection
    {
        return $this->ligas;
    }

    public function addLiga(Liga $liga): self
    {
        if (!$this->ligas->contains($liga)) {
            $this->ligas[] = $liga;
            $liga->addEquipo($this);
        }

        return $this;
    }

    public function removeLiga(Liga $liga): self
    {
        if ($this->ligas->removeElement($liga)) {
            $liga->removeEquipo($this);
        }

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }
}
