<?php

namespace App\MessageHandler\Command;

use App\Message\Command\ResetPasswordEmail;
use App\Repository\UserRepository;
use App\Service\EmailNotification;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ResetPasswordEmailHandler implements MessageHandlerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var EmailNotification
     */
    private EmailNotification $emailNotification;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * ResetPasswordEmailHandler constructor.
     *
     * @param EmailNotification $emailNotification
     * @param UserRepository    $userRepository
     */
    public function __construct(EmailNotification $emailNotification, UserRepository $userRepository)
    {
        $this->emailNotification = $emailNotification;
        $this->userRepository = $userRepository;
    }

    public function __invoke(ResetPasswordEmail $resetPasswordEmail)
    {
        $user = $this->userRepository->find($resetPasswordEmail->userId);

        if (!$user) {
            // could throw an exception... it would be retried
            // or return and this message will be discarded
            if ($this->logger) {
                $this->logger->alert(sprintf('User post %d was missing!', $resetPasswordEmail->userId));
            }
            return;
        }

        $this->emailNotification->sendEmail(
            'emails/forgotten_password.html.twig',
            ["forgottenPassword" => $user->getForgottenPassword()],
            $user->getFullName(),
            $user->getEmail(),
        );
    }
}
