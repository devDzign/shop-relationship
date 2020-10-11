<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Producer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        $producer = new Producer();

        $producer
            ->setPassword($this->userPasswordEncoder->encodePassword($producer, 'password'))
            ->setFirstname('Jane')
            ->setLastname('Doe')
            ->setDisplayName('producer')
            ->setEmail('producer@mail.com');

        $manager->persist($producer);

        $customer = new Customer();

        $customer
            ->setPassword($this->userPasswordEncoder->encodePassword($producer, 'password'))
            ->setFirstname('Jane')
            ->setLastname('Doe')
            ->setDisplayName('customer')
            ->setEmail('customer@mail.com');

        $manager->persist($customer);

        $manager->flush();
    }
}
