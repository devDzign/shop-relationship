<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class EmailNotification
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * EmailNotification constructor.
     *
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param string $template
     * @param array  $argumentsContext
     * @param string $fullName
     * @param string $from
     * @param string $to
     *
     * @throws TransportExceptionInterface
     */
    public function sendEmail(
        string $template,
        array $argumentsContext,
        string $fullName,
        string $from,
        string $to = 'hello@producteurauconsommateur.com'
    ): void {
        $email = (new TemplatedEmail())
            ->to(new Address($from, $fullName))
            ->from($to)
            ->context($argumentsContext)
            ->htmlTemplate($template);

        $this->mailer->send($email);
    }
}
