<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="utilisateur")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"producer"="App\Entity\Producer", "customer"="App\Entity\Customer"})
 * @UniqueEntity("email")
 * @UniqueEntity("displayName")
 */
abstract class User implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected UuidInterface $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column
     * @var string
     *
     */
    protected string $firstname = "";

    /**
     * @Assert\NotBlank()
     * @ORM\Column
     * @var string
     */
    protected string $lastname = "";
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(unique=true)
     * @var string
     */
    protected string $email = "";

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="4")
     * @ORM\Column
     * @var string
     */
    protected string $displayName = "";

    /**
     * @ORM\Column
     * @var string
     */
    protected string $password = "";

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="8")
     * @var null|string
     */
    protected ?string $plainPassword = null;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var \DateTimeImmutable
     */
    protected \DateTimeImmutable $registerAt;

    /**
     * @ORM\Embedded(class="ForgottenPassword")
     */
    protected ?ForgottenPassword $forgottenPassword;


    public function __construct()
    {
        $this->registerAt = new \DateTimeImmutable();
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname(string $firstname): User
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname(string $lastname): User
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     *
     * @return User
     */
    public function setDisplayName(string $displayName): User
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }



    /**
     * @return \DateTimeImmutable
     */
    public function getRegisterAt(): \DateTimeImmutable
    {
        return $this->registerAt;
    }

    /**
     * @param \DateTimeImmutable $registerAt
     *
     * @return User
     */
    public function setRegisterAt(\DateTimeImmutable $registerAt): User
    {
        $this->registerAt = $registerAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     *
     * @return User
     */
    public function setPlainPassword(?string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function hasForgotHisPassword(): void
    {
        $this->forgottenPassword = new ForgottenPassword();
    }

    /**
     * @return ForgottenPassword|null
     */
    public function getForgottenPassword(): ?ForgottenPassword
    {
        return $this->forgottenPassword;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        return sprintf("%s %s", $this->firstname, $this->lastname);
    }
}
