<?php

namespace Olenaza\BlogBundle\Controller;

use Olenaza\BlogBundle\Entity\Category;
use Olenaza\BlogBundle\Entity\Comment;
use Olenaza\BlogBundle\Entity\Post;
use Olenaza\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    /**
     * @param $page
     *
     * @return Response
     */
    public function listAction($page)
    {
        $breadcrumbs = $this->get('blog.breadcrumbs_builder')->createBreadcrumbs();

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
     * @param Category $category
     * @param $page
     *
     * @return Response
     */
    public function listByCategoryAction(Category $category, $page)
    {
        $breadcrumbs = $this->get('blog.breadcrumbs_builder')->createBreadcrumbs($category->getSlug());

        $query = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Post')
            ->findByCategory($category->getSlug());

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 5);

        return $this->render('OlenazaBlogBundle:post:index.html.twig', [
            'pagination' => $pagination,
            'categorySlug' => $category->getSlug(),
        ]);
    }

    /**
     * @param $name
     * @param $page
     *
     * @return Response
     */
    public function listByTagAction($name, $page)
    {
        $breadcrumbs = $this->get('blog.breadcrumbs_builder')->createBreadcrumbs(null, $name);

        $query = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Post')
            ->findByTag($name);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 5);

        return $this->render('OlenazaBlogBundle:post:index.html.twig', [
            'pagination' => $pagination,
            'tagName' => $name,
        ]);
    }

    /**
     * @param Post    $post
     * @param Request $request
     *
     * @return Response
     */
    public function showAction(Post $post, Request $request)
    {
        $breadcrumbs = $this->get('blog.breadcrumbs_builder')
            ->createBreadcrumbs($request->query->get('categorySlug'), $request->query->get('tagName'));

        $breadcrumbs->addItem($post->getTitle());

        $comment = new Comment($post);

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('post_show', ['slug' => $post->getSlug()]);
        }

        return $this->render('OlenazaBlogBundle:post:post_show.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
}
