<?php

namespace App\Admin;


use App\Entity\Payment;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\Type\Filter\DateType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\DateTimeRangeFilter;
use Sonata\Form\Type\DatePickerType;
use Sonata\Form\Type\DateRangePickerType;

class PaymentAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'payment';

    protected $baseRouteName = 'payment';

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
        '_sort_by' => 'transactionNo',
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Payment', array('class' => 'col-md-6'))
//            ->add('id')
            ->add('transactionNo')
            ->add('amount')
            ->add('subAmount')
            ->add('paymentType')
            ->add('purchases')
            ->add('description')
            ->add('sumUpRef')
            ->add('refunded')
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('transactionNo')
            ->add('paymentType')
            ->add('refunded')
            ->add('created', DateTimeRangeFilter::class, [
                'field_type'    => DateRangePickerType::class,
            ])
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('transactionNo')
            ->add('amount')
            ->add('subAmount')
            ->add('paymentType')
            ->add('created')
            ->add('purchases')
            ->add('description')
            ->add('sumUpRef')
            ->add('refunded')
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
            ->add('transactionNo')
            ->add('created', null, ['format' => 'd.m.y H:i:s'])
            ->add('amount')
            ->add('subAmount')
            ->add('paymentType')
            ->add('purchases')
            ->add('description')
            ->add('sumUpRef')
            ->add('refunded')
        ;
    }

    public function toString($object)
    {
        return $object->getTransactionNo();
    }
}
