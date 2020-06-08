<?php

namespace App\Admin;


use App\Entity\PurchaseItem;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PurchaseItemAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'purchaseitem';

    protected $baseRouteName = 'purchaseitem';

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
//            ->add('description')
//            ->add('amount')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
//            ->add('purchaseItems')
            ->add('product')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('product')
            ->add('description')
            ->add('amount')
            ->add('_action',
                    null,
                    [
                        'actions' => []
                    ]
                )
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('product')
            ->add('description')
            ->add('amount')
        ;
    }

    public function toString($object)
    {
        return $object instanceof PurchaseItem
            ? $object->getId().""
            : 'Purchase Item';
    }
}
