<?php

namespace Olenaza\BlogBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use FOS\UserBundle\Model\UserInterface;

class UserRegisteredWithOAuthEvent extends Event
{
    const NAME = 'user.oauth_registered';

    protected $user;

    protected $password;

    /**
     * @param UserInterface $user
     * @param string        $password
     */
    public function __construct(UserInterface $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
