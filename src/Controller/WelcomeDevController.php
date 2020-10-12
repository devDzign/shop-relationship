<?php

namespace App\Controller;

use App\Message\Query\GetTotalUsersCount;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeDevController extends AbstractController
{
    /**
     * @Route("/welcome", name="welcome_dev")
     */
    public function welcome()
    {

        $envelope = $queryBus->dispatch(new GetTotalUsersCount());
        /** @var HandledStamp $handled */
        $handled = $envelope->last(HandledStamp::class);

        return $this->render('welcome_dev/index.html.twig', [
            "var_react" => [
                     "message" => "super valeur recuperer de php",
                     "nbUsers" => $handled->getResult()
                ]
        ]);
    }
}
