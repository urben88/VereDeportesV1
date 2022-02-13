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

    public function __construct()
    {
        $this->solicitas = new ArrayCollection();
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
}
