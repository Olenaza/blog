<?php

namespace Olenaza\BlogBundle\Admin;

use Olenaza\BlogBundle\Entity\Setting;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class SettingAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'settings';

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('text', null, [
                'help' => '* These fields should not be blank',
            ])
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title', null, [
                'route' => ['name' => 'show'],
                'header_style' => 'width: 50%; text-align: center',
            ])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                ],
                'header_style' => 'width: 20%; text-align: center',
                'row_align' => 'center',
            ])
            ->add('slug', null, [
                'header_style' => 'width: 20%; text-align: center',
                'row_align' => 'center',
            ])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title')
            ->add('text')
        ;
    }

    /**
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof Setting
            ? $object->getTitle()
            : 'Page/Setting'; // shown in the breadcrumb on the create view
    }
}
