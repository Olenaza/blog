<?php

namespace Olenaza\BlogBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseFOSUBProvider;
use Symfony\Component\Security\Core\User\UserInterface;
use Olenaza\BlogBundle\Entity\User;
use Olenaza\BlogBundle\Event\UserRegisteredWithOAuthEvent;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;

class MyFOSUBUserProvider extends BaseFOSUBProvider
{
    private $dispatcher;

    /**
     * @param TraceableEventDispatcher $dispatcher
     */
    public function setEventDispatcher(TraceableEventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $service = $response->getResourceOwner()->getName();

        $socialID = $response->getUsername(); //a unique user identifier in an authentication configuration

        $user = $this->userManager->findUserBy([$this->getProperty($response) => $socialID]);

        //check if the user already has the corresponding social account
        if (null === $user) {
            //check if the user has a normal account
            $email = $response->getEmail();
            $user = $this->userManager->findUserByEmail($email);

            if (null === $user || !$user instanceof UserInterface) {
                //if the user does not have a normal account, set it up:
                $user = new User();

                $password = uniqid();

                $user->setUsername($response->getRealName());
                $user->setEmail($email);
                $user->setPlainPassword($password);
                $user->setEnabled(true);

                $event = new UserRegisteredWithOAuthEvent($user, $password);
                $this->dispatcher->dispatch(UserRegisteredWithOAuthEvent::NAME, $event);
            }
            //then set its corresponding social id
            switch ($service) {
                case 'google':
                    $user->setGoogleID($socialID);
                    break;
                case 'facebook':
                    $user->setFacebookID($socialID);
                    break;
            }

            $this->userManager->updateUser($user);
        } else {
            // else update access token of existing user
            $setter = 'set'.ucfirst($service).'AccessToken';
            $user->$setter($response->getAccessToken()); //update access token
        }

        return $user;
    }
}
