<?php

namespace Olenaza\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    /**
     * Show posts list.
     *
     * @return Response
     */
    public function listAction($page)
    {
        $post[0] = [
            'title' => '1st post title',
            'slug' => '1st_post_slug',
            'beginning' => '1st post begining',
            'text' => '1st post text',
            'published' => true,
        ];

        $post[1] = [
            'title' => '2nd post title',
            'slug' => '2nd_post_slug',
            'beginning' => '2nd post begining',
            'text' => '2nd post text',
            'published' => false,
        ];

        return $this->render('OlenazaBlogBundle:Post:index.html.twig', [
            'posts' => $post,
            'page' => $page,
        ]);
    }

    /**
     * Show post details.
     *
     * @return Response
     */
    public function showAction($slug)
    {
        $access = true;
        $post = [
            'title' => 'Post(found by slug) title',
            'slug' => $slug,
            'beginning' => 'Post(found by slug) begining',
            'text' => 'Post(found by slug) text',
            'published' => true,
            'tags' => [],
        ];

        return $this->render('OlenazaBlogBundle:Post:post_show.html.twig', [
            'post' => $post,
            'access' => $access,
        ]);
    }

    /**
     * Edit post details.
     *
     * @return Response
     */
    public function editAction($slug)
    {
        $response = new Response();
        $response->setContent('Form to change post '.$slug);
        $response->headers->set('Content-Type', 'text/plain');
        $response->setStatusCode(Response::HTTP_OK);

        return $response;
    }

    /**
     * Delete post.
     *
     * @return Response
     */
    public function deleteAction($slug)
    {
        $response = new Response();
        $response->setContent('Post'.$slug.' was deleted');
        $response->headers->set('Content-Type', 'text/plain');
        $response->setStatusCode(Response::HTTP_OK);

        return $response;
    }
}
