<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Address
 * @package App\Entity
 * @ORM\Embeddable()
 */
class Address
{

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    private ?string $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $restAddress;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[A-Za-z0-9]{5}$/")
     */
    private ?string $zipCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private ?string $city;

    /**
     * @ORM\Embedded(class="Position")
     * @Assert\Valid
     */
    private ?Position $position;


    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getRestAddress(): ?string
    {
        return $this->restAddress;
    }

    public function setRestAddress(?string $restAddress): self
    {
        $this->restAddress = $restAddress;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPosition(): ?Position
    {
        return $this->position;
    }

    public function setPosition(?Position $position): self
    {
        $this->position = $position;

        return $this;
    }
}
