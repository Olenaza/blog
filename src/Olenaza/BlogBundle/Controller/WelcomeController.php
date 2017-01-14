<?php

namespace Olenaza\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $posts = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Post')
            ->findAllOrderedByPublicationDate(3)
            ->getResult()
        ;

        return $this->render('OlenazaBlogBundle:welcome:welcome_page.html.twig', [
            'posts' => $posts,
        ]);
    }
}
