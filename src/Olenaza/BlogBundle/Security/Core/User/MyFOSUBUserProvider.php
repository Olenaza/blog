<?php

namespace Olenaza\BlogBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseFOSUBProvider;
use Symfony\Component\Security\Core\User\UserInterface;
use Olenaza\BlogBundle\Entity\User;

class MyFOSUBUserProvider extends BaseFOSUBProvider
{
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
                $user->setUsername($response->getRealName());
                $user->setEmail($email);
                $user->setPlainPassword(md5(uniqid()));
                $user->setEnabled(true);
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
