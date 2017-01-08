<?php

namespace Olenaza\BlogBundle\Admin;

use Olenaza\BlogBundle\Entity\Tag;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class TagAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'tag';

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Tag', ['class' => 'col-md-6'])
                ->add('name', null, [
                    'help' => '* This field is required',
                ])
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', null, [
                'header_style' => 'width: 70%; text-align: center',
            ])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
                'header_style' => 'text-align: center',
                'row_align' => 'center',
            ])
        ;
    }

    /**
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof Tag
            ? $object->getName()
            : 'Tag'; // shown in the breadcrumb on the create view
    }
}
