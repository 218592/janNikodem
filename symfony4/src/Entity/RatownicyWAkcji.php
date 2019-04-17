<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RatownicyWAkcjiRepository")
 */
class RatownicyWAkcji
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ratownikId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $czy_Kam;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $akcja_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRatownikId(): ?int
    {
        return $this->ratownikId;
    }

    public function setRatownikId(?int $ratownikId): self
    {
        $this->ratownikId = $ratownikId;

        return $this;
    }

    public function getCzyKam(): ?string
    {
        return $this->czy_Kam;
    }

    public function setCzyKam(?string $czy_Kam): self
    {
        $this->czy_Kam = $czy_Kam;

        return $this;
    }

    public function getAkcjaId(): ?int
    {
        return $this->akcja_id;
    }

    public function setAkcjaId(?int $akcja_id): self
    {
        $this->akcja_id = $akcja_id;

        return $this;
    }
}
