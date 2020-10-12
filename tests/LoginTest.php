<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class LoginTest extends WebTestCase
{
    /**
     * @param string $email
     *
     * @dataProvider provideEmails
     */
    public function testSuccessfulLogin(string $email): void
    {
        $client = static::createClient();

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");

        $crawler = $client->request(Request::METHOD_GET, $router->generate("security_login"));

        $form = $crawler->filter("form[name=login]")->form(
            [
                "email"    => $email,
                "password" => "password"
            ]
        );

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    /**
     * @return \Generator
     */
    public function provideEmails(): \Generator
    {
        yield ['producer@mail.com'];
        yield ['customer@mail.com'];
    }

//    /**
//     * @param string $email
//     *
//     * @dataProvider provideEmails
//     */
//    public function testInvalidCsrfTokenLogin(string $email): void
//    {
//        $client = static::createClient();
//
//        /** @var RouterInterface $router */
//        $router = $client->getContainer()->get("router");
//
//        $crawler = $client->request(Request::METHOD_GET, $router->generate("security_login"));
//
//        $form = $crawler->filter("form[name=login]")->form(
//            [
//                "_csrf_token" => "fail",
//                "email"       => $email,
//                "password"    => "password"
//            ]
//        );
//
//        $client->submit($form);
//
//        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
//
//        $client->followRedirect();
//
//        $this->assertSelectorTextContains("div.alert-danger", 'Jeton CSRF invalide.');
//    }

//    public function testInvalidCredentials(): void
//    {
//        $client = static::createClient();
//
//        /** @var RouterInterface $router */
//        $router = $client->getContainer()->get("router");
//
//        $crawler = $client->request(Request::METHOD_GET, $router->generate("security_login"));
//
//        $form = $crawler->filter("form[name=login]")->form(
//            [
//                "email"    => "producer@mail.com",
//                "password" => "fail"
//            ]
//        );
//
//        $client->submit($form);
//
//        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
//
//        $client->followRedirect();
//
//        $this->assertSelectorTextContains("div.alert-danger", 'Identifiants invalides.');
//    }
//
//    public function testInvalidEmail(): void
//    {
//        $client = static::createClient();
//
//        /** @var RouterInterface $router */
//        $router = $client->getContainer()->get("router");
//
//        $crawler = $client->request(Request::METHOD_GET, $router->generate("security_login"));
//
//        $form = $crawler->filter("form[name=login]")->form(
//            [
//                "email"    => "fail@email.com",
//                "password" => "password"
//            ]
//        );
//
//        $client->submit($form);
//
//        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
//
//        $client->followRedirect();
//
//        $this->assertSelectorTextContains("div.alert-danger", 'Cette adresse email n\'existe pas.');
//    }
}
