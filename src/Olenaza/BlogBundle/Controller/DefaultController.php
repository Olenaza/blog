<?php

namespace Olenaza\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OlenazaBlogBundle:Default:index.html.twig');
    }
}
