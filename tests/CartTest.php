<?php

namespace App\Tests;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class FarmTest
 * @package App\Tests
 */
class CartTest extends WebTestCase
{
    use AuthenticationTrait;

    public function testSuccessfulAddToCart(): void
    {
        $client = static::createAuthenticatedClient("customer@mail.com");

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get("doctrine.orm.entity_manager");

        $product = $entityManager->getRepository(Product::class)->findOneBy([]);

        $client->request(Request::METHOD_GET, $router->generate("cart_add", [
            "id" => $product->getId()
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $crawler = $client->request(Request::METHOD_GET, $router->generate("cart_index"));

        $this->assertEquals(1, $crawler->filter("tbody > tr")->count());

        $form = $crawler->filter("form[name=cart]")->form([
            "cart[cart][0][quantity]" => 0
        ]);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $crawler = $client->followRedirect();

        $this->assertEquals(0, $crawler->filter("tbody > tr")->count());
    }
}
