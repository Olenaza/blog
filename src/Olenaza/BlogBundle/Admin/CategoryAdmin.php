<?php

namespace Olenaza\BlogBundle\Admin;

use Olenaza\BlogBundle\Entity\Category;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CategoryAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'category';

    /**
     * Configure form fields for category entity on admin page.
     *
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Category', ['class' => 'col-md-6'])
            ->add('title', null, [
                'help' => '* This field is required',
            ])
            ->add('parent', 'sonata_type_model', [
                'class' => 'Olenaza\BlogBundle\Entity\Category',
                'property' => 'title',
                'required' => false,
            ])
            ->end();
    }

    /**
     * * Configure filters for category entity on admin page.
     *
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('parent');
    }

    /**
     * * Configure list fields for category entity on admin page.
     *
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('parent', null, [
                'header_style' => 'width: 35%; text-align: center',
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

    /**
     * * Configure show fields for the category on admin page.
     *
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title')
            ->add('parent')
        ;
    }

    /**
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof Category
            ? $object->getTitle()
            : 'Category'; // shown in the breadcrumb on the create view
    }
}
