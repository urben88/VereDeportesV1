<?php

namespace App\Entity;

use App\Repository\CampoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CampoRepository::class)
 */
class Campo
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
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=Partido::class, mappedBy="id_campo")
     */
    private $partidos;

    /**
     * @ORM\OneToMany(targetEntity=Reserva::class, mappedBy="id_campo")
     */
    private $reservas;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $deporte;

    public function __construct()
    {
        $this->partidos = new ArrayCollection();
        $this->reservas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $partido->setIdCampo($this);
        }

        return $this;
    }

    public function removePartido(Partido $partido): self
    {
        if ($this->partidos->removeElement($partido)) {
            // set the owning side to null (unless already changed)
            if ($partido->getIdCampo() === $this) {
                $partido->setIdCampo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reserva[]
     */
    public function getReservas(): Collection
    {
        return $this->reservas;
    }

    public function addReserva(Reserva $reserva): self
    {
        if (!$this->reservas->contains($reserva)) {
            $this->reservas[] = $reserva;
            $reserva->setIdCampo($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): self
    {
        if ($this->reservas->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getIdCampo() === $this) {
                $reserva->setIdCampo(null);
            }
        }

        return $this;
    }

    public function getDeporte(): ?string
    {
        return $this->deporte;
    }

    public function setDeporte(?string $deporte): self
    {
        $this->deporte = $deporte;

        return $this;
    }
}
