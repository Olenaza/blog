<?php

namespace Olenaza\BlogBundle\Form\Type;

use Olenaza\BlogBundle\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PostType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title');

        $builder->add('subtitle');

        $builder->add('text', null, [
            'label' => 'Text **',
            'required' => false,
        ]);

        $builder->add('coverImage', null, [
            'label' => 'Cover Image **',
            'required' => false,
        ]);

        $builder->add('published', CheckboxType::class, [
                'label' => 'Publish this post?',
                'required' => false,
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $post = $event->getData();
            $form = $event->getForm();

            if (!$post->isPublished()) {
                $form->add('publishedOn', DateType::class, [
                    'required' => false,
                    'years' => [date('Y'), date('Y') + 1],
                    'placeholder' => [
                        'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                    ],
                ]);
            }
        });

        $builder->add('tags', EntityType::class, [
            'class' => 'OlenazaBlogBundle:Tag',
            'label' => 'Tags',
            'attr' => ['class' => 'select2'],
            'choice_label' => 'getName',
            'multiple' => true,
        ]);

        $builder->add('save', SubmitType::class, [
            'attr' => ['class' => 'save'],
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                'data_class' => Post::class,
                'validation_groups' => [
                    Post::class,
                    'determineValidationGroups',
                ],
                'error_mapping' => [
                    'isSubtitleDifferentThanTitle' => 'subtitle',
                ],
        ]);
    }
}
