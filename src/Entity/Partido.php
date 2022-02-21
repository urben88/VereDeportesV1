<?php

namespace App\Entity;

use App\Repository\PartidoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PartidoRepository::class)
 */
class Partido
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $equipo1;

    /**
     * @ORM\Column(type="integer")
     */
    private $equipo2;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_partido;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $resul_equipo1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $resul_equipo2;

    /**
     * @ORM\ManyToOne(targetEntity=Liga::class, inversedBy="partidos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_liga;

    /**
     * @ORM\ManyToOne(targetEntity=user::class, inversedBy="partidos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_profesor;

    /**
     * @ORM\ManyToOne(targetEntity=Campo::class, inversedBy="partidos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_campo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fecha_acaba;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEquipo1(): ?int
    {
        return $this->equipo1;
    }

    public function setEquipo1(int $equipo1): self
    {
        $this->equipo1 = $equipo1;

        return $this;
    }

    public function getEquipo2(): ?int
    {
        return $this->equipo2;
    }

    public function setEquipo2(int $equipo2): self
    {
        $this->equipo2 = $equipo2;

        return $this;
    }

    public function getFechaPartido(): ?\DateTimeInterface
    {
        return $this->fecha_partido;
    }

    public function setFechaPartido(\DateTimeInterface $fecha_partido): self
    {
        $this->fecha_partido = $fecha_partido;

        return $this;
    }

    public function getResulEquipo1(): ?int
    {
        return $this->resul_equipo1;
    }

    public function setResulEquipo1(?int $resul_equipo1): self
    {
        $this->resul_equipo1 = $resul_equipo1;

        return $this;
    }

    public function getResulEquipo2(): ?int
    {
        return $this->resul_equipo2;
    }

    public function setResulEquipo2(?int $resul_equipo2): self
    {
        $this->resul_equipo2 = $resul_equipo2;

        return $this;
    }

    public function getIdLiga(): ?Liga
    {
        return $this->id_liga;
    }

    public function setIdLiga(?Liga $id_liga): self
    {
        $this->id_liga = $id_liga;

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

    public function getIdCampo(): ?campo
    {
        return $this->id_campo;
    }

    public function setIdCampo(?campo $id_campo): self
    {
        $this->id_campo = $id_campo;

        return $this;
    }

    public function getFechaAcaba(): ?\DateTimeInterface
    {
        return $this->fecha_acaba;
    }

    public function setFechaAcaba(\DateTimeInterface $fecha_acaba): self
    {
        $this->fecha_acaba = $fecha_acaba;

        return $this;
    }
}
