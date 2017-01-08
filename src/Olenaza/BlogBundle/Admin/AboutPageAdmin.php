<?php

namespace Olenaza\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class AboutPageAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'about';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['show', 'edit']);
    }

    public function getDashboardActions()
    {
        $actions = parent::getDashboardActions();

        $actions['show'] = array(
            'label' => 'Show',
            'url' => $this->generateUrl('show', ['id' => 1]),
            'icon' => 'search-plus',
            'translation_domain' => 'SonataAdminBundle',
        );

        $actions['edit'] = array(
            'label' => 'Edit',
            'url' => $this->generateUrl('edit', ['id' => 1]),
            'icon' => 'edit',
            'translation_domain' => 'SonataAdminBundle',
        );

        return $actions;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('text', null, [
            'label' => 'Content',
            'help' => '* This field should not be blank',
        ]);
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper->add('text', null, [
            'label' => 'Content',
        ]);
    }

    /**
     * @return string
     */
    public function toString($object)
    {
        return 'About Page';
    }
}
