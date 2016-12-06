<?php

namespace Olenaza\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AboutController extends Controller
{
    /**
     * Show about page.
     *
     * @return Response
     */
    public function indexAction()
    {
        $info = 'Тут є інформація про автора';

        $urlEdit = $this->generateUrl('about_edit');

        $urlDelete = $this->generateUrl('about_delete');

        return $this->render('OlenazaBlogBundle:About:index.html.twig', [
            'access' => true,
            'info' => $info,
            'urlEdit' => $urlEdit,
            'urlDelete' => $urlDelete,
        ]);
    }

    /**
     * Show form to change about page.
     *
     * @return Response
     */
    public function editAction()
    {
        $response = new Response();
        $response->setContent('Form to change about page');
        $response->headers->set('Content-Type', 'text/plain');
        $response->setStatusCode(Response::HTTP_OK);

        return $response;
    }

    /**
     * Delete info about the author.
     *
     * @return Response
     */
    public function deleteAction()
    {
        $urlEdit = $this->generateUrl('about_edit');

        $urlDelete = $this->generateUrl('about_delete');

        return $this->render('OlenazaBlogBundle:About:index.html.twig', [
            'access' => true,
            'urlEdit' => $urlEdit,
            'urlDelete' => $urlDelete,
        ]);
    }
}
