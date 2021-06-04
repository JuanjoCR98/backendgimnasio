<?php

namespace App\Entity;

use App\Repository\RutinaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RutinaRepository::class)
 */
class Rutina
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
     * @ORM\Column(type="date")
     */
    private $fecha_creacion;

    /**
     * @ORM\ManyToOne(targetEntity=Usuario::class, inversedBy="rutinas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usuario;

    /**
     * @ORM\OneToMany(targetEntity=EjercicioRutina::class, mappedBy="rutina", orphanRemoval=true)
     */
    private $ejercicios;

    public function __construct()
    {
        $this->ejercicios = new ArrayCollection();
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

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion(\DateTimeInterface $fecha_creacion): self
    {
        $this->fecha_creacion = $fecha_creacion;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * @return Collection|EjercicioRutina[]
     */
    public function getEjercicios(): Collection
    {
        return $this->ejercicios;
    }

    public function addEjercicio(EjercicioRutina $ejercicio): self
    {
        if (!$this->ejercicios->contains($ejercicio)) {
            $this->ejercicios[] = $ejercicio;
            $ejercicio->setRutina($this);
        }

        return $this;
    }

    public function removeEjercicio(EjercicioRutina $ejercicio): self
    {
        if ($this->ejercicios->removeElement($ejercicio)) {
            // set the owning side to null (unless already changed)
            if ($ejercicio->getRutina() === $this) {
                $ejercicio->setRutina(null);
            }
        }

        return $this;
    }
}
