<?php

namespace Olenaza\BlogBundle\Controller;

use Olenaza\BlogBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\View\View as ViewClass;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\View;

/**
 * @RouteResource("Post")
 */
class ApiPostController extends Controller
{
    /**
     * @param string $page
     *
     * @return ViewClass
     *
     * @View(serializerGroups={"list"}, serializerEnableMaxDepthChecks=true)
     *
     * @ApiDoc()
     */
    public function cgetAction($page)
    {
        $view = ViewClass::create();

        $query = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Post')
            ->findAllOrderedByPublicationDate();

        $paginator = $this->get('knp_paginator');
        $limit = $this->container->getParameter('posts_per_page');
        $pagination = $paginator->paginate($query, $page, $limit);

        $view->setData($pagination);

        return $view;
    }

    /**
     * @param $slug
     *
     * @return ViewClass
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @ApiDoc()
     */
    public function getAction($slug)
    {
        $view = ViewClass::create();

        $post = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Post')
            ->findOneBy(['slug' => $slug]);

        $view->setData($post);

        return $view;
    }

    /**
     * @param $slug
     *
     * @return ViewClass
     *
     * @ApiDoc()
     */
    public function getCommentsAction($slug)
    {
    }
}
