<?php

namespace Olenaza\BlogBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseFOSUBProvider;
use Symfony\Component\Security\Core\User\UserInterface;

class MyFOSUBUserProvider extends BaseFOSUBProvider
{
    /**
     * {@inheritdoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        // get property from provider configuration by provider name
        // , it will return `facebook_id` in that case (see service definition below)
        $property = $this->getProperty($response);
        $username = $response->getUsername();

        //we "disconnect" previously connected users
        $existingUser = $this->userManager->findUserBy(array($property => $username));
        if (null !== $existingUser) {
            // set current user id and token to null for disconnect
            $this->userManager->updateUser($existingUser);
        }
        // we connect current user, set current user id and token
        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $userEmail = $response->getEmail();
        $user = $this->userManager->findUserByEmail($userEmail);

        // if null just create new user and set it properties
        if (null === $user) {
            $username = $response->getRealName();
            $user = new User();
            $user->setUsername($username);
            $user->setEmail($userEmail);

            $facebookId = $this->getProperty($response);
            $user->setFacebookId($facebookId);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $user;
        }
        // else update access token of existing user
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set'.ucfirst($serviceName).'AccessToken';
        $user->$setter($response->getAccessToken()); //update access token

        return $user;
    }
}
