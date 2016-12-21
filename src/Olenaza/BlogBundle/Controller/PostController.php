<?php

namespace Olenaza\BlogBundle\Controller;

use Olenaza\BlogBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
     * Create new post.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function newAction(Request $request)
    {
        $post = new Post();

        $tags = $this->getDoctrine()->getRepository('OlenazaBlogBundle:Tag')->findAll();

        $form = $this->createFormBuilder($post)
            ->add('title', TextType::class)
            ->add('subtitle', TextType::class)
            ->add('text', TextType::class)
            ->add('coverImage', TextType::class)
            ->add('publishOn', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Post'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            print_r($post);
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $em = $this->getDoctrine()->getManager();
            // $em->persist($task);
            // $em->flush();

            return $this->redirectToRoute('post_new');
        }

        return $this->render('OlenazaBlogBundle:Post:post_form.html.twig', array(
            'form' => $form->createView(),
            'tags' => $tags,
        ));
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
