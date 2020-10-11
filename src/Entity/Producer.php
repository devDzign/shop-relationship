<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * Class Producer
 * @package App\Entity
 */
class Producer extends User
{
    public const ROLE = "producer";


    public function getRoles(): array
    {
        return ["ROLE_PRODUCER"];
    }
}
