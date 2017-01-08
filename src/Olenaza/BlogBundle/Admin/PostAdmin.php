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

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Content')
                ->add('title', null, [
                    'help' => '* This field is required',
                ])
                ->add('subtitle')
                ->add('text', null, [
                    'required' => false,
                    'label' => 'Text **',
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
                ->add('tags', 'sonata_type_model', [
                    'class' => 'Olenaza\BlogBundle\Entity\Tag',
                    'multiple' => true,
                    'property' => 'name',
                    'help' => '* This field is required',
                ])
            ->end()

            ->with('Publish Options', ['class' => 'col-md-6'])
                ->add('published', null, [
                    'label' => 'Publish this post?',
                    'required' => false,
                    ])
            ->end()
        ;

        $subject = $this->getSubject();

        if (!$subject->isPublished()) {
            $formMapper
                ->with('Publish Options', ['class' => 'col-md-6'])
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

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('tags')
            ->add('published')
        ;
    }

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
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
                'header_style' => 'width: 20%; text-align: center',
                'row_align' => 'center',
            ])
        ;
    }

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

    public function preUpdate($object)
    {
        $object->setPublishedOn(null);
    }
}
