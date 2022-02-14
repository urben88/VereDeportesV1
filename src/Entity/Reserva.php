<?php

namespace App\Entity;

use App\Repository\ReservaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservaRepository::class)
 */
class Reserva
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

   

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
    private $fecha_caduca;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="reservas")
     */
    private $id_usuario;

    /**
     * @ORM\ManyToOne(targetEntity=Campo::class, inversedBy="reservas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_campo;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="reservas_profesor")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_profesor;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFechaCaduca(): ?\DateTimeInterface
    {
        return $this->fecha_caduca;
    }

    public function setFechaCaduca(?\DateTimeInterface $fecha_caduca): self
    {
        $this->fecha_caduca = $fecha_caduca;

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

    public function getIdCampo(): ?Campo
    {
        return $this->id_campo;
    }

    public function setIdCampo(?Campo $id_campo): self
    {
        $this->id_campo = $id_campo;

        return $this;
    }

    public function getIdProfesor(): ?user
    {
        return $this->id_profesor;
    }

    public function setIdProfesor(?user $id_profesor): self
    {
        $this->id_profesor = $id_profesor;

        return $this;
    }
}
