<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UsuarioRepository::class)
 */
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
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
     * @ORM\Column(type="string", length=255)
     */
    private $apellidos;

    /**
     * @ORM\Column(type="date")
     */
    private $fecha_nacimiento;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rol;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $foto;

    /**
     * @ORM\OneToOne(targetEntity=RedSocial::class, mappedBy="usuario", cascade={"persist", "remove"})
     */
    private $redSocial;

    /**
     * @ORM\OneToMany(targetEntity=Estadistica::class, mappedBy="usuario", orphanRemoval=true)
     */
    private $estadisticas;

    /**
     * @ORM\OneToMany(targetEntity=Rutina::class, mappedBy="usuario", orphanRemoval=true)
     */
    private $rutinas;

    public function __construct()
    {
        $this->estadisticas = new ArrayCollection();
        $this->rutinas = new ArrayCollection();
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
    public function getUsername(): string
    {
        return (string) $this->email;
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

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): self
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->fecha_nacimiento;
    }

    public function setFechaNacimiento(\DateTimeInterface $fecha_nacimiento): self
    {
        $this->fecha_nacimiento = $fecha_nacimiento;

        return $this;
    }

    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(string $rol): self
    {
        $this->rol = $rol;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(?string $foto): self
    {
        $this->foto = $foto;

        return $this;
    }

    public function getRedSocial(): ?RedSocial
    {
        return $this->redSocial;
    }

    public function setRedSocial(RedSocial $redSocial): self
    {
        // set the owning side of the relation if necessary
        if ($redSocial->getUsuario() !== $this) {
            $redSocial->setUsuario($this);
        }

        $this->redSocial = $redSocial;

        return $this;
    }

    /**
     * @return Collection|Estadistica[]
     */
    public function getEstadisticas(): Collection
    {
        return $this->estadisticas;
    }

    public function addEstadistica(Estadistica $estadistica): self
    {
        if (!$this->estadisticas->contains($estadistica)) {
            $this->estadisticas[] = $estadistica;
            $estadistica->setUsuario($this);
        }

        return $this;
    }

    public function removeEstadistica(Estadistica $estadistica): self
    {
        if ($this->estadisticas->removeElement($estadistica)) {
            // set the owning side to null (unless already changed)
            if ($estadistica->getUsuario() === $this) {
                $estadistica->setUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rutina[]
     */
    public function getRutinas(): Collection
    {
        return $this->rutinas;
    }

    public function addRutina(Rutina $rutina): self
    {
        if (!$this->rutinas->contains($rutina)) {
            $this->rutinas[] = $rutina;
            $rutina->setUsuario($this);
        }

        return $this;
    }

    public function removeRutina(Rutina $rutina): self
    {
        if ($this->rutinas->removeElement($rutina)) {
            // set the owning side to null (unless already changed)
            if ($rutina->getUsuario() === $this) {
                $rutina->setUsuario(null);
            }
        }

        return $this;
    }
}
