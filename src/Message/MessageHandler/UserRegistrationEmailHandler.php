<?php

declare(strict_types=1);

namespace App\Message\MessageHandler;

use App\Message\UserRegistrationEmail;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UserRegistrationEmailHandler
{
    private LoggerInterface $logger;

    /**
     * UserRegistrationEmailHandler constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(UserRegistrationEmail $userRegistrationEmail)
    {
        // Get user data from database
        // Create an email from template

        // Send email
        sleep(5);

        // ... other stuff which can take a while ...

        $this->logger->info('Registration message was sent to user ' . $userRegistrationEmail->getUserId());
    }
}