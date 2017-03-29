<?php

namespace Olenaza\BlogBundle\Controller;

use Olenaza\BlogBundle\Entity\Comment;
use Olenaza\BlogBundle\Entity\Post;
use Olenaza\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\View\View as ViewClass;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @RouteResource("Post")
 */
class ApiPostController extends Controller
{
    /**
     * @param Request $request
     *
     * @return ViewClass
     *
     * @View(serializerEnableMaxDepthChecks=true,
     *     serializerGroups={"list", "Default"},
     *     statusCode=200
     *     )
     *
     * @ApiDoc()
     */
    public function cgetAction(Request $request)
    {
        $view = ViewClass::create();

        $query = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Post')
            ->findAllOrderedByPublicationDate();

        $page = $request->query->getInt('page', 1);

        $paginator = $this->get('knp_paginator');
        $limit = $this->container->getParameter('posts_per_page');
        $pagination = $paginator->paginate($query, $page, $limit);

        $posts = iterator_to_array($pagination);

        $route = 'api_get_posts';

        $createLinkUrl = function ($targetPage) use ($route) {
            return $this->generateUrl($route, ['page' => $targetPage]);
        };

        $_links = [];

        $_links['self'] = $createLinkUrl($page);
        $_links['first'] = $createLinkUrl(1);
        $_links['last'] = $createLinkUrl($pagination->getPageCount());

        if ($page > 1) {
            $_links['prev'] = $createLinkUrl($page - 1);
        }

        if ($page < $pagination->getPageCount()) {
            $_links['next'] = $createLinkUrl($page + 1);
        }

        $view->setData([
            'posts' => $posts,
            'total' => $pagination->getTotalItemCount(),
            'count' => count($posts),
            '_links' => $_links,
        ]);

        return $view;
    }

    /**
     * @param $slug
     *
     * @return ViewClass
     *
     * @View(serializerEnableMaxDepthChecks=true,
     *     serializerGroups={"details", "Default"},
     *     statusCode=200
     *     )
     *
     * @ApiDoc()
     */
    public function getAction($slug)
    {
        $view = ViewClass::create();

        $post = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Post')
            ->findOneBy(['slug' => $slug]);

        if (!$post) {
            throw $this->createNotFoundException(sprintf(
                "No post found with slug $slug"
            ));
        }

        $view->setData($post);

        return $view;
    }

    /**
     * @param $slug
     *
     * @return ViewClass
     *
     * @View(serializerEnableMaxDepthChecks=true,
     *     statusCode=200
     *     )
     *
     * @ApiDoc()
     */
    public function getCommentsAction($slug)
    {
        $view = ViewClass::create();

        $post = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Post')
            ->findOneBy(['slug' => $slug]);

        if (!$post) {
            throw $this->createNotFoundException(sprintf(
                "No post found with slug $slug"
            ));
        }

        $comments = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Comment')
            ->findAllByPost($slug);

        $view->setData([
            'comments' => $comments,
        ]);

        return $view;
    }

    /**
     * @param $slug
     * @param $id
     *
     * @return ViewClass
     *
     * @View(serializerEnableMaxDepthChecks=true,
     *     statusCode=200
     *     )
     *
     * @ApiDoc()
     */
    public function getCommentAction($slug, $id)
    {
        $view = ViewClass::create();

        $comment = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Comment')
            ->find($id);

        if (!$comment) {
            throw $this->createNotFoundException(sprintf(
               "No comment found with id $id"
            ));
        }

        $view->setData($comment);

        return $view;
    }

    /**
     * @param Request $request
     *
     * @return ViewClass
     *
     * @View(serializerEnableMaxDepthChecks=true,
     *     statusCode=201
     *     )
     *
     * @ApiDoc()
     */
    public function postCommentsAction($slug, Request $request)
    {
        $view = ViewClass::create();

        $post = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Post')
            ->findOneBy(['slug' => $slug]);

        if (!$post) {
            throw $this->createNotFoundException(sprintf(
                "No post found with slug $slug"
            ));
        }

        $user = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:User')
            ->findOneBy(['username' => 'Name0']);

        $comment = new Comment($post, $user);

        $form = $this->createForm(CommentType::class, $comment);

        $this->processForm($request, $form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        $view->setData($comment);

        $commentUrl = $this->generateUrl(
            'api_get_post_comment',
            ['slug' => $post->getSlug(), 'id' => $comment->getId()]
        );

        $view->setHeader('Location', $commentUrl);

        return $view;
    }

    /**
     * @param $slug
     * @param $id
     * @param Request $request
     *
     * @return ViewClass
     *
     * @View(serializerEnableMaxDepthChecks=true,
     *     statusCode=200
     *     )
     *
     * @ApiDoc()
     */
    public function putCommentAction($slug, $id, Request $request)
    {
        $view = ViewClass::create();

        $comment = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Comment')
            ->find($id);

        if (!$comment) {
            throw $this->createNotFoundException(sprintf(
                "No comment found with id $id"
            ));
        }

        $form = $this->createForm(CommentType::class, $comment);

        $this->processForm($request, $form);

        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        $view->setData($comment);

        return $view;
    }

    /**
     * @param $slug
     * @param $id
     *
     * @return ViewClass
     *
     * @View(serializerEnableMaxDepthChecks=true,
     *     statusCode=204
     *     )
     *
     * @ApiDoc()
     */
    public function deleteCommentAction($slug, $id)
    {
        $view = ViewClass::create();

        $comment = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Comment')
            ->find($id);

        if ($comment) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();
        }

        return $view;
    }

    /**
     * @param Request       $request
     * @param FormInterface $form
     */
    private function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);

        $form->submit($data);
    }
}
