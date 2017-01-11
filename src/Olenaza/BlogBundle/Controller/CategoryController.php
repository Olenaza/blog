<?php

namespace Olenaza\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    /**
     * Show posts by category.
     *
     * @param $categoryId
     *
     * @return Response
     */
    public function showPostsAction($categoryId)
    {
        $category = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Category')
            ->find($categoryId);

        $posts = $category->getPosts();

        return $this->render('OlenazaBlogBundle:post:index.html.twig', [
            'posts' => $posts,
        ]);
    }
}
