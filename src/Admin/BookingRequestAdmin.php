<?php
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class BookingRequestAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'bookingrequest';

    protected $baseRouteName = 'bookingrequest';

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
        // 			'_sort_by' => 'updatedAt',
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Booking Request', array('class' => 'col-md-6'))
//            ->add('id')
            ->add('name')
            ->add('phone')
            ->add('email')
            ->add('noPassengers')
            ->add('flight')
            ->add('flightDate')
            ->add('arrivalDate')
            ->add('departureDate')
            ->add('groupConditions')
            ->add('comments')
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('phone')
            ->add('email')
            ->add('noPassengers')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('phone')
            ->add('email')
            ->add('noPassengers')
            ->add('flight')
            ->add('flightDate')
            ->add('arrivalDate')
            ->add('departureDate')
            ->add('groupConditions')
            ->add('comments')
            ->add('_action', null, array(
                'actions' => array(
                    'edit' => [],
                    'delete' => [],
                )
            ))
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('phone')
            ->add('email')
            ->add('noPassengers')
            ->add('flight')
            ->add('flightDate')
            ->add('arrivalDate')
            ->add('departureDate')
            ->add('groupConditions')
            ->add('comments')
        ;
    }

}
