<?php

namespace Olenaza\BlogBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class SearchType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('searchText', TextType::class, [
            'label' => false,
            'attr' => [
                'placeholder' => 'Пошук',
            ],
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        $builder->add('submit', SubmitType::class, [
            'label' => ' ',
            'attr' => ['class' => 'glyphicon glyphicon-search'],
        ]);
    }
}
