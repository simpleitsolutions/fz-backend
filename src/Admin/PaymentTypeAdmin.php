<?php

namespace App\Admin;


use App\Entity\MeetingLocation;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PaymentTypeAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'payment_type';

    protected $baseRouteName = 'payment_type';

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
        '_sort_order' => 'ASC',

        // name of the ordered field (default = the model's id field, if any)
        '_sort_by' => 'sortOrder',
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Payment Type', array('class' => 'col-md-6'))
//            ->add('id')
            ->add('name')
            ->add('shortName')
            ->add('printName')
            ->add('iconPath')
            ->add('fee')
            ->add('sumUpPayment')
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('shortName')
            ->add('printName')
            ->add('sumUpPayment')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('shortName')
            ->add('printName')
            ->add('iconPath')
            ->add('fee')
            ->add('sumUpPayment')
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
            ->add('name')
            ->add('shortName')
            ->add('printName')
            ->add('iconPath')
            ->add('fee')
            ->add('sumUpPayment')
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
        return $object->getName()."";
    }

}
