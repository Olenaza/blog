<?php

namespace Olenaza\BlogBundle\Controller;

use Olenaza\BlogBundle\Entity\Comment;
use Olenaza\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function listAction($page)
    {
        $query = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Post')
            ->findAllOrderedByPublicationDate();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 5);

        return $this->render('OlenazaBlogBundle:post:index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @param $categorySlug
     * @param $page
     *
     * @return Response
     */
    public function listByCategoryAction($categorySlug, $page)
    {
        $query = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Post')
            ->findByCategory($categorySlug);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 5);

        return $this->render('OlenazaBlogBundle:post:index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @param $tagName
     * @param $page
     *
     * @return Response
     */
    public function listByTagAction($tagName, $page)
    {
        $query = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Post')
            ->findByTag($tagName);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 5);

        return $this->render('OlenazaBlogBundle:post:index.html.twig', [
            'pagination' => $pagination,
            'tagName' => $tagName,
        ]);
    }

    /**
     * @param $slug
     * @param Request $request
     *
     * @return Response
     */
    public function showAction($slug, Request $request)
    {
        $post = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Post')
            ->findOneBy([
                'slug' => $slug,
            ]);

        $comment = new Comment($post);

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPublishedAt(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('post_show', ['slug' => $slug]);
        }

        return $this->render('OlenazaBlogBundle:post:post_show.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
}
