<?php

namespace Olenaza\BlogBundle\EventListener;

use Swift_Mailer;
use Olenaza\BlogBundle\Emails\EmailGenerator;
use Olenaza\BlogBundle\Event\UserRegisteredWithOAuthEvent;

class UserOAuthRegistrationListener
{
    private $mailer;

    private $generator;

    /**
     * @param Swift_Mailer   $mailer
     * @param EmailGenerator $generator
     */
    public function __construct(Swift_Mailer $mailer, EmailGenerator $generator)
    {
        $this->mailer = $mailer;
        $this->generator = $generator;
    }

    /**
     * @param UserRegisteredWithOAuthEvent $event
     */
    public function onUserRegistration(UserRegisteredWithOAuthEvent $event)
    {
        $message = $this->generator->generateRegistartionMessage($event->getUser(), $event->getPassword());

        $this->mailer->send($message);

        return;
    }
}
