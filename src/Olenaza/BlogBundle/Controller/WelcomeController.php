<?php

namespace Olenaza\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    /**
     * Show home page.
     *
     * @return Response
     */
    public function indexAction()
    {
        $posts = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Post')
            ->findAllOrderedByPublicationDate(3);

        return $this->render('OlenazaBlogBundle:welcome:welcome_page.html.twig', [
            'posts' => $posts,
        ]);
    }
}
