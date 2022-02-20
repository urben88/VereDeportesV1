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
     * @ORM\ManyToMany(targetEntity=Equipo::class, inversedBy="ligas")
     */
    private $equipos;

    /**
     * @ORM\OneToMany(targetEntity=Partido::class, mappedBy="id_liga")
     */
    private $partidos;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_creacion;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha_termina;


    public function __construct()
    {
        $this->equipos = new ArrayCollection();
        $this->partidos = new ArrayCollection();
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

    /**
     * @return Collection|Partido[]
     */
    public function getPartidos(): Collection
    {
        return $this->partidos;
    }

    public function addPartido(Partido $partido): self
    {
        if (!$this->partidos->contains($partido)) {
            $this->partidos[] = $partido;
            $partido->setIdLiga($this);
        }

        return $this;
    }

    public function removePartido(Partido $partido): self
    {
        if ($this->partidos->removeElement($partido)) {
            // set the owning side to null (unless already changed)
            if ($partido->getIdLiga() === $this) {
                $partido->setIdLiga(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion(\DateTimeInterface $fecha_creacion): self
    {
        $this->fecha_creacion = $fecha_creacion;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getFechaTermina(): ?\DateTimeInterface
    {
        return $this->fecha_termina;
    }

    public function setFechaTermina(?\DateTimeInterface $fecha_termina): self
    {
        $this->fecha_termina = $fecha_termina;

        return $this;
    }

   
}
