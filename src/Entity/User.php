<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $capitan;

    /**
     * @ORM\OneToMany(targetEntity=Solicita::class, mappedBy="id_usuario")
     */
    private $solicitas;

    /**
     * @ORM\OneToMany(targetEntity=Reserva::class, mappedBy="id_usuario")
     */
    private $reservas;

    /**
     * @ORM\OneToMany(targetEntity=Partido::class, mappedBy="id_profesor")
     */
    private $partidos;

    /**
     * @ORM\OneToMany(targetEntity=Reserva::class, mappedBy="id_profesor")
     */
    private $reservas_profesor;

    /**
     * @ORM\ManyToOne(targetEntity=Equipo::class, inversedBy="jugadores")
     */
    private $equipo;

    public function __construct()
    {
        $this->solicitas = new ArrayCollection();
        $this->reservas = new ArrayCollection();
        $this->partidos = new ArrayCollection();
        $this->reservas_profesor = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getCapitan(): ?bool
    {
        return $this->capitan;
    }

    public function setCapitan(?bool $capitan): self
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
            $solicita->setIdUsuario($this);
        }

        return $this;
    }

    public function removeSolicita(Solicita $solicita): self
    {
        if ($this->solicitas->removeElement($solicita)) {
            // set the owning side to null (unless already changed)
            if ($solicita->getIdUsuario() === $this) {
                $solicita->setIdUsuario(null);
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
            $reserva->setIdUsuario($this);
        }

        return $this;
    }

    public function removeReserva(Reserva $reserva): self
    {
        if ($this->reservas->removeElement($reserva)) {
            // set the owning side to null (unless already changed)
            if ($reserva->getIdUsuario() === $this) {
                $reserva->setIdUsuario(null);
            }
        }

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
            $partido->setIdProfesor($this);
        }

        return $this;
    }

    public function removePartido(Partido $partido): self
    {
        if ($this->partidos->removeElement($partido)) {
            // set the owning side to null (unless already changed)
            if ($partido->getIdProfesor() === $this) {
                $partido->setIdProfesor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reserva[]
     */
    public function getReservasProfesor(): Collection
    {
        return $this->reservas_profesor;
    }

    public function addReservasProfesor(Reserva $reservasProfesor): self
    {
        if (!$this->reservas_profesor->contains($reservasProfesor)) {
            $this->reservas_profesor[] = $reservasProfesor;
            $reservasProfesor->setIdProfesor($this);
        }

        return $this;
    }

    public function removeReservasProfesor(Reserva $reservasProfesor): self
    {
        if ($this->reservas_profesor->removeElement($reservasProfesor)) {
            // set the owning side to null (unless already changed)
            if ($reservasProfesor->getIdProfesor() === $this) {
                $reservasProfesor->setIdProfesor(null);
            }
        }

        return $this;
    }

    public function getEquipo(): ?Equipo
    {
        return $this->equipo;
    }

    public function setEquipo(?Equipo $equipo): self
    {
        $this->equipo = $equipo;

        return $this;
    }
}
