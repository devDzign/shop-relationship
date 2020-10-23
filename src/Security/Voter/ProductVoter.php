<?php

namespace App\Security\Voter;

use App\Entity\Producer;
use App\Entity\Product;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductVoter extends Voter
{

    public const UPDATE = "update";
    public const DELETE = "delete";

    protected function supports($attribute, $subject)
    {

        return in_array($attribute, [self::UPDATE, self::DELETE])
            && $subject instanceof Product;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof Producer) {
            return false;
        }

        return $subject->getFarm() === $user->getFarm();
    }
}
