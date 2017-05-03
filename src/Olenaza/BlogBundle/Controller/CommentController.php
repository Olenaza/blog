<?php

namespace Olenaza\BlogBundle\Controller;

use Olenaza\BlogBundle\Entity\Comment;
use Olenaza\BlogBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    /**
     * @param Comment $comment
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Comment $comment, Request $request)
    {
        $this->denyAccessUnlessGranted('edit', $comment);

        $commentEditForm = $this->createForm(CommentType::class, $comment);

        $commentEditForm->handleRequest($request);

        if ($commentEditForm->isSubmitted() && $commentEditForm->isValid()) {
            $comment = $commentEditForm->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('post_show', ['slug' => $comment->getPost()->getSlug()]);
        }

        return $this->render('OlenazaBlogBundle:comment:_partials_comment_edit_form.html.twig', [
            'commentEditForm' => $commentEditForm->createView(),
            'id' => $comment->getId(),
        ]);
    }

    /**
     * @param Comment $comment
     * @param Request $request
     *
     * @return Response
     */
    public function deleteAction(Comment $comment, Request $request)
    {
        $this->denyAccessUnlessGranted('delete', $comment);

        $commentDeleteForm = $this->createForm(CommentType::class, $comment);

        $commentDeleteForm->handleRequest($request);

        if ($commentDeleteForm->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comment);
            $em->flush();

            return $this->redirectToRoute('post_show', ['slug' => $comment->getPost()->getSlug()]);
        }

        return $this->render('OlenazaBlogBundle:comment:_partials_comment_delete_form.html.twig', [
            'commentDeleteForm' => $commentDeleteForm->createView(),
            'id' => $comment->getId(),
        ]);
    }
}
