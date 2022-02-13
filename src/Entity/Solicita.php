<?php

namespace App\Entity;

use App\Repository\SolicitaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SolicitaRepository::class)
 */
class Solicita
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $aceptado;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_solicitud;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="solicitas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_usuario;

    /**
     * @ORM\ManyToOne(targetEntity=equipo::class, inversedBy="solicitas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_equipo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAceptado(): ?bool
    {
        return $this->aceptado;
    }

    public function setAceptado(bool $aceptado): self
    {
        $this->aceptado = $aceptado;

        return $this;
    }

    public function getFechaSolicitud(): ?\DateTimeInterface
    {
        return $this->fecha_solicitud;
    }

    public function setFechaSolicitud(\DateTimeInterface $fecha_solicitud): self
    {
        $this->fecha_solicitud = $fecha_solicitud;

        return $this;
    }

    public function getIdUsuario(): ?user
    {
        return $this->id_usuario;
    }

    public function setIdUsuario(?user $id_usuario): self
    {
        $this->id_usuario = $id_usuario;

        return $this;
    }

    public function getIdEquipo(): ?equipo
    {
        return $this->id_equipo;
    }

    public function setIdEquipo(?equipo $id_equipo): self
    {
        $this->id_equipo = $id_equipo;

        return $this;
    }
}
