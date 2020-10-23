<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * Class Producer
 * @package App\Entity
 * @ORM\EntityListeners({"App\EntityListener\ProducerListener"})
 */
class Producer extends User
{
    public const ROLE = "producer";

    /**
     * @ORM\OneToOne(targetEntity=Farm::class, inversedBy="producer", cascade={"persist", "remove"})
     */
    private $farm;




    public function getRoles(): array
    {
        return ["ROLE_PRODUCER"];
    }

    public function getFarm(): ?Farm
    {
        return $this->farm;
    }

    public function setFarm(?Farm $farm): self
    {
        $this->farm = $farm;

        return $this;
    }
}
