<?php

namespace App\Admin;


use App\Entity\Product;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ProductAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'product';

    protected $baseRouteName = 'product';

    protected $searchResultActions = ['show'];

    public function getNewInstance()
    {
        $instance = parent::getNewInstance();

        return $instance;
    }

    protected $datagridValues = array(

        // display the first page (default = 1)
        '_page' => 1,

        // reverse order (default = 'ASC')
        '_sort_order' => 'DESC',

        // name of the ordered field (default = the model's id field, if any)
//        '_sort_by' => 'advertised'
    );

    public function createQuery($context = 'list')
    {
        $proxyQuery = parent::createQuery('list');
        $proxyQuery->addOrderBy($proxyQuery->getRootAlias() . '.advertised', 'DESC');
        $proxyQuery->addOrderBy($proxyQuery->getRootAlias() . '.sortOrder', 'ASC');
        return $proxyQuery;
    }
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Product', array('class' => 'col-md-6'))
//            ->add('id')
            ->add('description')
            ->add('shortName')
            ->add('price')
            ->add('advertised')
            ->add('preferred')
            ->add('productCategory')
            ->add('sortOrder')
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('description')
            ->add('productCategory')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('description')
            ->add('productCategory')
            ->add('shortName')
            ->add('price')
            ->add('advertised')
            ->add('preferred')
            ->add('sortOrder')
            ->add('_action', null, array(
                'actions' => array(
                    'edit' => ['template' => '/sonataadmin/CRUD/list__action_edit.html.twig'],
                    'delete' => ['template' => '/sonataadmin/CRUD/list__action_delete.html.twig'],
                )
            ))
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('description')
            ->add('productCategory')
            ->add('shortName')
            ->add('price')
            ->add('advertised')
            ->add('preferred')
            ->add('sortOrder')
        ;
    }

    public function configureBatchActions($actions)
    {
        if (isset($actions['delete'])) {
            unset($actions['delete']);
        }

        return $actions;
    }

    public function toString($object)
    {
        return $object->getDescription();
    }
}
