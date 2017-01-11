<?php

namespace Olenaza\BlogBundle\Controller;

use Olenaza\BlogBundle\Entity\Comment;
use Olenaza\BlogBundle\Entity\Post;
use Olenaza\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
        $posts = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Post')
            ->findAll();

        return $this->render('OlenazaBlogBundle:post:index.html.twig', [
            'posts' => $posts,
            'page' => $page,
        ]);
    }

    /**
     * Show posts list sorted by publication date.
     *
     * @return Response
     */
    public function listByPublicationDateAction($page)
    {
        $posts = $this->getDoctrine()
            ->getRepository('OlenazaBlogBundle:Post')
            ->findAllOrderedByPublicationDate();

        return $this->render('OlenazaBlogBundle:post:index.html.twig', [
            'posts' => $posts,
            'page' => $page,
        ]);
    }

    /**
     * Show post details.
     *
     * @return Response
     */
    public function showAction($slug, Request $request)
    {
        $access = true;

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
            'access' => $access,
            'form' => $form->createView(),
        ]);
    }
}
