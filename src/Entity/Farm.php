<?php

namespace App\Entity;

use App\Repository\FarmRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FarmRepository::class)
 */
class Farm
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     * @Groups({"read"})
     */
    private ?UuidInterface $id = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"read"})
     */
    private ?string $name = "";

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description = "";

    /**
     * @ORM\OneToOne(targetEntity=Producer::class, mappedBy="farm")
     */
    private ?Producer $producer = null;

    /**
     * @ORM\Embedded(class="Address")
     * @Assert\Valid()
     * @Groups({"read"})
     */
    private ?Address $address = null;



    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProducer(): ?Producer
    {
        return $this->producer;
    }

    public function setProducer(?Producer $producer): self
    {
        $this->producer = $producer;

        // set (or unset) the owning side of the relation if necessary
        $newFarm = null === $producer ? null : $this;
        if ($producer->getFarm() !== $newFarm) {
            $producer->setFarm($newFarm);
        }

        return $this;
    }

    /**
     * @return Address|null
     */
    public function getAddress(): ?Address
    {
        return $this->address;
    }

    /**
     * @param Address|null $address
     */
    public function setAddress(?Address $address): void
    {
        $this->address = $address;
    }
}
