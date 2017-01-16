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
     * @param Request $request
     *
     * @return Response
     */
    public function listAction($page)
    {
        $breadcrumbs = $this->get('white_october_breadcrumbs');

        $breadcrumbs
            ->addRouteItem('Домівка', 'welcome')
            ->addItem('Усі записи')
        ;

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
    public function listByCategoryAction(Category $category, $page)
    {
        $breadcrumbs = $this->get('white_october_breadcrumbs');

        $breadcrumbs
            ->addItem($category->getTitle())
            ->prependItem($category->getParent()->getTitle())
            ->prependRouteItem('Усі записи', 'posts_list')
            ->prependRouteItem('Домівка', 'welcome')
        ;

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
     * @param $tagName
     * @param $page
     *
     * @return Response
     */
    public function listByTagAction($name, $page)
    {
        $breadcrumbs = $this->get('white_october_breadcrumbs');

        $breadcrumbs
            ->addRouteItem('Домівка', 'welcome')
            ->addRouteItem('Усі записи', 'posts_list')
            ->addItem("Тег $name")
        ;

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
     * @param $slug
     * @param Request $request
     *
     * @return Response
     */
    public function showAction(Post $post, Request $request)
    {
        $breadcrumbs = $this->get('white_october_breadcrumbs');

        if (!empty($request->query->get('categorySlug'))) {
            $category = $this->getDoctrine()
                ->getRepository('OlenazaBlogBundle:Category')
                ->findOneBy(['slug' => $request->query->get('categorySlug')]);

            $breadcrumbs
                ->addItem('Запис')
                ->prependRouteItem($category->getTitle(), 'posts_list_by_category', [
                    'slug' => $category->getSlug(),
                ])
                ->prependItem($category->getParent()->getTitle())
                ->prependRouteItem('Усі записи', 'posts_list')
                ->prependRouteItem('Домівка', 'welcome')
            ;
        } elseif (!empty($request->query->get('tagName'))) {
            $tagName = $request->query->get('tagName');

            $breadcrumbs
                ->addRouteItem('Домівка', 'welcome')
                ->addRouteItem('Усі записи', 'posts_list')
                ->addRouteItem("Тег $tagName", 'posts_list_by_tag', [
                    'name' => $tagName,
                ])
                ->addItem('Запис')
            ;
        } else {
            $breadcrumbs
                ->addRouteItem('Домівка', 'welcome')
                ->addRouteItem('Усі записи', 'posts_list')
                ->addItem('Запис')
            ;
        }

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
