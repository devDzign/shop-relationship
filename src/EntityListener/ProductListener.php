<?php

namespace App\EntityListener;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

/**
 * Class ProductListener
 * @package App\EntityListener
 */
class ProductListener
{
    /**
     * @var Security
     */
    private Security $security;

    /**
     * ProductListener constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param Product $product
     */
    public function prePersist(Product $product): void
    {
        if ($product->getFarm() !== null) {
            return;
        }

        /** @var User $user */
        $user =  $this->security->getUser();
        $product->setFarm($user->getFarm());
    }
}
