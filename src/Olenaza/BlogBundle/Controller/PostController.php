<?php

namespace Olenaza\BlogBundle\Controller;

use Olenaza\BlogBundle\Entity\Comment;
use Olenaza\BlogBundle\Entity\Post;
use Olenaza\BlogBundle\Form\Type\PostType;
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
     * Create new post.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function newAction(Request $request)
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            $slug = $this->get('blog.slugger')->slugify($post->getTitle(), $post->getId());
            $post->setSlug($slug);

            $post->setBeginning($post->getSubtitle());

            if (!$post->isPublished()) {
                $post->setPublishedOn(null);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $this->addFlash('notice', 'Congratulations, your post has been successfully created!');

            return $this->redirectToRoute('posts_list');
        }

        $formTitle = 'Create new post';

        return $this->render('OlenazaBlogBundle:post:post_form.html.twig', [
            'form_title' => $formTitle,
            'form' => $form->createView(),
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

    /**
     * Edit post details.
     *
     * @return Response
     */
    public function editAction($slug, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('OlenazaBlogBundle:Post')->findOneBy([
                'slug' => $slug,
            ]);

        if (!$post) {
            throw $this->createNotFoundException('No post found for slug '.$slug);
        }

        $editForm = $this->createForm(PostType::class, $post);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $this->addFlash('notice', 'Congratulations, your post has been successfully updated!');

            return $this->redirectToRoute('post_show', ['slug' => $slug]);
        }
        $formTitle = 'Edit the post';

        return $this->render('OlenazaBlogBundle:post:post_form.html.twig', [
            'form_title' => $formTitle,
            'form' => $editForm->createView(),
        ]);
    }

    /**
     * Delete post.
     *
     * @return Response
     */
    public function deleteAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('OlenazaBlogBundle:Post')->findOneBy([
            'slug' => $slug,
        ]);

        if (!$post) {
            throw $this->createNotFoundException('No post found for slug '.$slug);
        }

        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('posts_list', ['page' => 1]);
    }
}
