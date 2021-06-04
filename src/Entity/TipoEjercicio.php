<?php

namespace App\Entity;

use App\Repository\TipoEjercicioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoEjercicioRepository::class)
 */
class TipoEjercicio
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
    private $tipo;

    /**
     * @ORM\OneToMany(targetEntity=Ejercicio::class, mappedBy="tipoEjercicio", orphanRemoval=true)
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

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * @return Collection|Ejercicio[]
     */
    public function getEjercicios(): Collection
    {
        return $this->ejercicios;
    }

    public function addEjercicio(Ejercicio $ejercicio): self
    {
        if (!$this->ejercicios->contains($ejercicio)) {
            $this->ejercicios[] = $ejercicio;
            $ejercicio->setTipoEjercicio($this);
        }

        return $this;
    }

    public function removeEjercicio(Ejercicio $ejercicio): self
    {
        if ($this->ejercicios->removeElement($ejercicio)) {
            // set the owning side to null (unless already changed)
            if ($ejercicio->getTipoEjercicio() === $this) {
                $ejercicio->setTipoEjercicio(null);
            }
        }

        return $this;
    }
}
