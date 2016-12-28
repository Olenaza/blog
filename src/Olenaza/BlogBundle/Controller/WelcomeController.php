<?php

namespace Olenaza\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    /**
     * Show home page.
     *
     * @return Response
     */
    public function indexAction()
    {
        $post[0] = [
            'title' => 'Most Recent post title',
            'slug' => 'most_recent_post_slug',
            'beginning' => 'Most Recent post begining',
            'text' => 'Most Recent post text',
            'published' => true,
        ];

        $post[1] = [
            'title' => 'Recent post title',
            'slug' => 'recent_post_slug',
            'beginning' => 'Recent post begining',
            'text' => 'Recent post text',
            'published' => true,
        ];

        return $this->render('OlenazaBlogBundle:welcome:welcome_page.html.twig', [
            'posts' => $post,
        ]);
    }
}
