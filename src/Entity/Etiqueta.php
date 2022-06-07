<?php

namespace App\Entity;

use App\Repository\EtiquetaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtiquetaRepository::class)
 */
class Etiqueta
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
     * @ORM\ManyToMany(targetEntity=Entrada::class, mappedBy="etiquetas")
     */
    private $entradas;

    public function __construct()
    {
        $this->entradas = new ArrayCollection();
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
     * @return Collection<int, Entrada>
     */
    public function getEntradas(): Collection
    {
        return $this->entradas;
    }

    public function addEntrada(Entrada $entrada): self
    {
        if (!$this->entradas->contains($entrada)) {
            $this->entradas[] = $entrada;
            $entrada->addEtiqueta($this);
        }

        return $this;
    }

    public function removeEntrada(Entrada $entrada): self
    {
        if ($this->entradas->removeElement($entrada)) {
            $entrada->removeEtiqueta($this);
        }

        return $this;
    }
}
