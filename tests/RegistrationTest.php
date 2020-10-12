<?php

namespace App\Tests;

use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class RegistrationTest extends WebTestCase
{

    /**
     * @param string $role
     * @param array $formData
     * @dataProvider provideRoles
     */
    public function testSuccessfulRegistration(string $role, array $formData): void
    {
        $client = static::createClient();

        /** @var RouterInterface $router */
        $router = $client->getContainer()->get("router");

        $crawler = $client->request(Request::METHOD_GET, $router->generate("app.sign-up", [
            "role" => $role
        ]));

        $form = $crawler->filter("form[name=registration]")->form($formData);

        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    /**
     * @return Generator
     */
    public function provideRoles(): Generator
    {
        yield ['producer', [
            "registration[email]" => "email@email.com",
            "registration[displayName]" => "email@email.com",
            "registration[plainPassword]" => "password",
            "registration[firstName]" => "John",
            "registration[lastName]" => "Doe"
        ]];
        yield ['producer', [
            "registration[email]" => "email@email.com",
            "registration[displayName]" => "email@email.com",
            "registration[plainPassword]" => "password",
            "registration[firstName]" => "John",
            "registration[lastName]" => "Doe",
        ]];
        yield ['customer', [
            "registration[email]" => "email@email.com",
            "registration[displayName]" => "email@email.com",
            "registration[plainPassword]" => "password",
            "registration[firstName]" => "John",
            "registration[lastName]" => "Doe"
        ]];
    }
}
