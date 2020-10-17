<?php

namespace App\Service;

class LongCalcul
{


    /**
     * @return int[]
     */
    public function getLongCalcul(): array
    {
        sleep(3);

        return [1,2,3];
    }
}
