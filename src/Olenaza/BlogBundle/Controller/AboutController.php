<?php

namespace Olenaza\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AboutController extends Controller
{
    /**
     * Show About page content.
     *
     * @return Response
     */
    public function indexAction()
    {
        $content = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:AboutPage')
            ->findOneBy(['id' => 1]);

        return $this->render('OlenazaBlogBundle:about:index.html.twig', [
            'about' => $content,
        ]);
    }
}
