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
        $limit = $this->container->getParameter('recent_posts_number');

        $posts = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Post')
            ->findAllOrderedByPublicationDate($limit)
            ->getResult()
        ;

        return $this->render('OlenazaBlogBundle:welcome:welcome_page.html.twig', [
            'posts' => $posts,
        ]);
    }
}
