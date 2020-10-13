<?php

namespace App\MessageHandler\Query;

use App\Message\Query\GetTotalUsersCount;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetTotalUsersCountHandler implements MessageHandlerInterface
{


    public function __invoke(GetTotalUsersCount $getTotalUsersCount)
    {
        return 50;
    }
}
