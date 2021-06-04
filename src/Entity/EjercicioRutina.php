<?php

namespace App\Entity;

use App\Repository\EjercicioRutinaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EjercicioRutinaRepository::class)
 */
class EjercicioRutina
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tiempo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $series;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $repeticiones;

    /**
     * @ORM\ManyToOne(targetEntity=Rutina::class, inversedBy="ejercicios")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rutina;

    /**
     * @ORM\ManyToOne(targetEntity=Ejercicio::class, inversedBy="ejerciciosrutina")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ejercicio;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTiempo(): ?int
    {
        return $this->tiempo;
    }

    public function setTiempo(?int $tiempo): self
    {
        $this->tiempo = $tiempo;

        return $this;
    }

    public function getSeries(): ?int
    {
        return $this->series;
    }

    public function setSeries(?int $series): self
    {
        $this->series = $series;

        return $this;
    }

    public function getRepeticiones(): ?int
    {
        return $this->repeticiones;
    }

    public function setRepeticiones(?int $repeticiones): self
    {
        $this->repeticiones = $repeticiones;

        return $this;
    }

    public function getRutina(): ?Rutina
    {
        return $this->rutina;
    }

    public function setRutina(?Rutina $rutina): self
    {
        $this->rutina = $rutina;

        return $this;
    }

    public function getEjercicio(): ?Ejercicio
    {
        return $this->ejercicio;
    }

    public function setEjercicio(?Ejercicio $ejercicio): self
    {
        $this->ejercicio = $ejercicio;

        return $this;
    }
}
