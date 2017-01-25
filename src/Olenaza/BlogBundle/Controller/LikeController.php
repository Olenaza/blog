<?php

namespace Olenaza\BlogBundle\Controller;

use Olenaza\BlogBundle\Entity\Post;
use Olenaza\BlogBundle\Entity\User;
use Olenaza\BlogBundle\Entity\Like;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LikeController extends Controller
{
    public function createAction(Post $post, User $user)
    {
        $like = new Like($post, $user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($like);
        $em->flush();

        return $this->redirectToRoute('post_show', ['slug' => $post->getSlug()]);
    }
}
