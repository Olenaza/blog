<?php

namespace Olenaza\BlogBundle\Controller;

use Olenaza\BlogBundle\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TagController extends Controller
{
    /**
     * Create new tag.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function newAction(Request $request)
    {
        $tag = new Tag();

        $form = $this->createFormBuilder($tag)
            ->add('name', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Create Tag'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();

            $this->addFlash('notice', 'Congratulations, your tag has been successfully created!');

            return $this->redirectToRoute('posts_list');
        }

        return $this->render('OlenazaBlogBundle:post:tag_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
