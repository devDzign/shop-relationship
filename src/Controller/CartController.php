<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Form\CartType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CartController
 * @package App\Controller
 * @Route("/cart")
 * @IsGranted("ROLE_CUSTOMER")
 */
class CartController extends AbstractController
{

    /**
     * @Route("add/{id}", name="cart_add")
     * @param Product $product
     *
     * @return RedirectResponse
     */
    public function add(Product $product): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $user->addToCart($product);

        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', 'Le produit a bien été ajouté à votre panier.');

        return $this->redirectToRoute('farm_show', ['id' => $product->getFarm()->getId()]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @Route("/", name="cart_index")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(CartType::class, $this->getUser())->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                "success",
                "Votre panier a été modifiée avec succès."
            );

            return $this->redirectToRoute("cart_index");
        }

        return $this->render(
            "ui/cart/index.html.twig",
            [
                "form" => $form->createView(),
            ]
        );
    }
}
