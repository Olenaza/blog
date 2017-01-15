<?php

namespace Olenaza\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AboutController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $aboutPage = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Setting')
            ->findOneBy(['slug' => 'about-page']);

        return $this->render('OlenazaBlogBundle:about:index.html.twig', [
            'about' => $aboutPage,
        ]);
    }
}
