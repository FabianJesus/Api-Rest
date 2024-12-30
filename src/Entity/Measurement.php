<?php

namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MeasurementRepository::class)]
class Measurement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['wine:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['wine:read'])]
    private ?int $year = null;

    #[ORM\ManyToOne(inversedBy: 'measurements')]
    #[Groups(['wine:read'])]
    private ?Sensor $sensor = null;

    #[ORM\ManyToOne(inversedBy: 'measurements')]
    private ?Wine $wine = null;

    #[ORM\Column(length: 255)]
    #[Groups(['wine:read'])]
    private ?string $color = null;

    #[ORM\Column(length: 50)]
    #[Groups(['wine:read'])]
    private ?string $temperature = null;

    #[ORM\Column(length: 50)]
    #[Groups(['wine:read'])]
    private ?string $graduation = null;

    #[ORM\Column(length: 50)]
    #[Groups(['wine:read'])]
    private ?string $ph = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getSensor(): ?Sensor
    {
        return $this->sensor;
    }

    public function setSensor(?Sensor $sensor): static
    {
        $this->sensor = $sensor;

        return $this;
    }

    public function getWine(): ?Wine
    {
        return $this->wine;
    }

    public function setWine(?Wine $wine): static
    {
        $this->wine = $wine;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getTemperature(): ?string
    {
        return $this->temperature;
    }

    public function setTemperature(string $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getGraduation(): ?string
    {
        return $this->graduation;
    }

    public function setGraduation(string $graduation): static
    {
        $this->graduation = $graduation;

        return $this;
    }

    public function getPh(): ?string
    {
        return $this->ph;
    }

    public function setPh(string $ph): static
    {
        $this->ph = $ph;

        return $this;
    }
}
