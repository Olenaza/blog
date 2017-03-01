<?php

namespace Olenaza\BlogBundle\Admin;

use Olenaza\BlogBundle\Entity\Post;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PostAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'post';

    protected $searchResultActions = array('show');

    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by' => 'publishedOn',
    ];

    /**
     * Configure form fields for post entity on admin page.
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Content')
                ->add('title', null, [
                    'help' => '* This field is required',
                ])
                ->add('subtitle')
                ->add('text', 'sonata_simple_formatter_type', [
                    'required' => false,
                    'label' => 'Text **',
                    'format' => 'richhtml',
                    'attr' => ['class' => 'ckeditor'],
                    ])
                ->add('beginning', null, [
                    'required' => false,
                    'label' => 'Beginning **',
                    ])
                ->add('coverImage', null, [
                    'required' => false,
                    'label' => 'Cover Image **',
                    'help' => '** These fields are required if you choose to publish the post',
                    ])
            ->end()

            ->with('Meta data', ['class' => 'col-md-6'])
                ->add('categories', 'sonata_type_model', [
                    'class' => 'Olenaza\BlogBundle\Entity\Category',
                    'multiple' => true,
                    'property' => 'title',
                    'help' => '* This field is required',
                ])
                ->add('tags', 'sonata_type_model', [
                    'class' => 'Olenaza\BlogBundle\Entity\Tag',
                    'multiple' => true,
                    'property' => 'name',
                    'help' => '* This field is required',
                ])
            ->end()

        ;

        $subject = $this->getSubject();

        if (!$subject->isPublished()) {
            $formMapper
                ->with('Publish Options', ['class' => 'col-md-6'])
                    ->add('forPublication', null, [
                        'label' => 'Publish this post?',
                        'required' => false,
                    ])

                    ->add('publishedOn', 'date', [
                        'label' => 'Publish On',
                        'required' => false,
                        'years' => [date('Y'), date('Y') + 1],
                        'data' => new \DateTime('today'),
                    ])
                ->end()
            ;
        }
    }

    /**
     * Configure filters for post entity on admin page.
     *
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('categories')
            ->add('tags')
            ->add('published')
        ;
    }

    /**
     * Configure list fields for post entity on admin page.
     *
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title', null, [
                'route' => ['name' => 'show'],
                'header_style' => 'width: 20%; text-align: center',
            ])
            ->add('subtitle', null, [
                'header_style' => 'width: 20%; text-align: center',
            ])
            ->add('categories', null, [
                'header_style' => 'width: 15%; text-align: center',
            ])
            ->add('tags', null, [
                'header_style' => 'text-align: center',
            ])
            ->add('published', null, [
                'header_style' => 'width: 5%; text-align: center',
                'row_align' => 'center',
            ])
            ->add('publishedOn', null, [
                'header_style' => 'width: 10%; text-align: center',
            ])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                ],
                'header_style' => 'width: 10%; text-align: center',
                'row_align' => 'center',
            ])
        ;
    }

    /**
     * Configure show fields for the post.
     *
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title')
            ->add('subtitle')
            ->add('beginning')
            ->add('publishedOn')
            ->add('text')
            ->add('coverImage')
        ;
    }

    /**
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof Post
            ? $object->getTitle()
            : 'Post'; // shown in the breadcrumb on the create view
    }

    /**
     * configure set publishedOn field on object creation.
     *
     * @param mixed $object
     */
    public function prePersist($object)
    {
        if (!($object->isForPublication() or $object->isPublished())) {
            $object->setPublishedOn(null);
        }
    }

    /**
     * configure set publishedOn field on object update.
     *
     * @param mixed $object
     */
    public function preUpdate($object)
    {
        if (!($object->isForPublication() or $object->isPublished())) {
            $object->setPublishedOn(null);
        }
    }
}
