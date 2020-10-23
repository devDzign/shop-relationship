<?php

namespace App\Message\Command;

use Ramsey\Uuid\UuidInterface;

class ResetPasswordEmail
{
    /**
     * @var UuidInterface
     */
    public UuidInterface $userId;

    /**
     * ResetPasswordEmail constructor.
     *
     * @param UuidInterface $userId
     */
    public function __construct(UuidInterface $userId)
    {
        $this->userId = $userId;
    }
}
