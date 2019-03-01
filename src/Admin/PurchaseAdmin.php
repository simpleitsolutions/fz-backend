<?php

namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PurchaseAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'purchase';

    protected $baseRouteName = 'purchase';

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

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Purchase', array('class' => 'col-md-6'))
//            ->add('id')
            ->add('purchaseItems')
            ->add('payments')
            ->add('passenger')
            ->add('voucher')
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('passenger')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('purchaseItems')
            ->add('payments')
            ->add('passenger')
            ->add('voucher')
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
            ->add('purchaseItems')
            ->add('payments')
            ->add('passenger')
            ->add('voucher')
        ;
    }

}
