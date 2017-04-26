<?php

namespace Olenaza\BlogBundle\Emails;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;

class EmailGenerator
{
    private $templating;

    /**
     * @param TwigEngine $templating
     * @param $fromAddress
     */
    public function __construct(TwigEngine $templating, $fromAddress)
    {
        $this->templating = $templating;
        $this->fromAddress = $fromAddress;
    }

    /**
     * @param UserInterface $user
     * @param $password
     *
     * @return mixed
     */
    public function generateRegistartionMessage(UserInterface $user, $password)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Вітаємо! Ви зареєструвалися на Cheblog!')
            ->setFrom($this->fromAddress)
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render(
                    'OlenazaBlogBundle:emails:registration.html.twig',
                    [
                        'username' => $user->getUsername(),
                        'password' => $password,
                    ]
                ),
                'text/html'
            )
            ->addPart(
                $this->templating->render(
                    'OlenazaBlogBundle:emails:registration.text.twig',
                    [
                        'username' => $user->getUsername(),
                        'password' => $password,
                    ]
                ),
                'text/plain'
            )
        ;

        return $message;
    }
}
