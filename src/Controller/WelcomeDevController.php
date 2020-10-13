<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeDevController extends AbstractController
{
    /**
     * @Route("/welcome", name="welcome_dev")
     */
    public function welcome()
    {
        return $this->render('welcome_dev/index.html.twig');
    }
}
