<?php

namespace Olenaza\BlogBundle\Form\Type;

use Olenaza\BlogBundle\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', null, [
            'label' => 'Leave comment',
            'required' => false,
        ]);

        $builder->add('save', SubmitType::class, [
            'label' => 'Send',
            'attr' => ['class' => 'save'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
