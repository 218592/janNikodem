<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rani
 *
 * @ORM\Table(name="rani")
 * @ORM\Entity
 */
class Rani
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="opaska_id", type="integer", nullable=true)
     */
    private $opaskaId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ratownik_id", type="integer", nullable=true)
     */
    private $ratownikId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="akcja_id", type="integer", nullable=true)
     */
    private $akcjaId;

    /**
     * @var float|null
     *
     * @ORM\Column(name="szerokosc_geo", type="float", precision=10, scale=0, nullable=true)
     */
    private $szerokoscGeo;

    /**
     * @var float|null
     *
     * @ORM\Column(name="dlugosc_geo", type="float", precision=10, scale=0, nullable=true)
     */
    private $dlugoscGeo;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="nadawanie", type="boolean", nullable=true, options={"default"="0"})
     */
    private $nadawanie = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="aktywna_opaska", type="boolean", nullable=true, options={"default"="0"})
     */
    private $aktywnaOpaska = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="tetno", type="integer", nullable=true)
     */
    private $tetno;

    /**
     * @var string|null
     *
     * @ORM\Column(name="kolor", type="string", length=32, nullable=true)
     */
    private $kolor;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="w_akcji", type="boolean", nullable=true, options={"default"="1"})
     */
    private $wAkcji = '1';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $data = 'CURRENT_TIMESTAMP';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpaskaId(): ?int
    {
        return $this->opaskaId;
    }

    public function setOpaskaId(?int $opaskaId): self
    {
        $this->opaskaId = $opaskaId;

        return $this;
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

    public function getAkcjaId(): ?int
    {
        return $this->akcjaId;
    }

    public function setAkcjaId(?int $akcjaId): self
    {
        $this->akcjaId = $akcjaId;

        return $this;
    }

    public function getSzerokoscGeo(): ?float
    {
        return $this->szerokoscGeo;
    }

    public function setSzerokoscGeo(?float $szerokoscGeo): self
    {
        $this->szerokoscGeo = $szerokoscGeo;

        return $this;
    }

    public function getDlugoscGeo(): ?float
    {
        return $this->dlugoscGeo;
    }

    public function setDlugoscGeo(?float $dlugoscGeo): self
    {
        $this->dlugoscGeo = $dlugoscGeo;

        return $this;
    }

    public function getNadawanie(): ?bool
    {
        return $this->nadawanie;
    }

    public function setNadawanie(?bool $nadawanie): self
    {
        $this->nadawanie = $nadawanie;

        return $this;
    }

    public function getAktywnaOpaska(): ?bool
    {
        return $this->aktywnaOpaska;
    }

    public function setAktywnaOpaska(?bool $aktywnaOpaska): self
    {
        $this->aktywnaOpaska = $aktywnaOpaska;

        return $this;
    }

    public function getTetno(): ?int
    {
        return $this->tetno;
    }

    public function setTetno(?int $tetno): self
    {
        $this->tetno = $tetno;

        return $this;
    }

    public function getKolor(): ?string
    {
        return $this->kolor;
    }

    public function setKolor(?string $kolor): self
    {
        $this->kolor = $kolor;

        return $this;
    }

    public function getWAkcji(): ?bool
    {
        return $this->wAkcji;
    }

    public function setWAkcji(?bool $wAkcji): self
    {
        $this->wAkcji = $wAkcji;

        return $this;
    }

    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }

    public function setData(\DateTimeInterface $data): self
    {
        $this->data = $data;

        return $this;
    }


}
