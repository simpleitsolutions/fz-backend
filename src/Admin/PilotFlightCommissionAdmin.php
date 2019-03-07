<?php

namespace App\Admin;


use App\Entity\PilotFlightCommission;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PilotFlightCommissionAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'pilot_flight_commission';

    protected $baseRouteName = 'pilot_flight_commission';

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
        '_sort_by' => 'pilot.name',
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Commission', array('class' => 'col-md-6'))
//            ->add('id')
            ->add('pilot')
            ->add('flight')
            ->add('flightCommission')
            ->add('activeEndDate')
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('pilot')
            ->add('flight')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('pilot.name')
            ->add('flight.description')
            ->add('flightCommission')
            ->add('activeEndDate')
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
            ->add('pilot')
            ->add('flight')
            ->add('flightCommission')
            ->add('activeEndDate')
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
        return $object->getPilot()->getName();
    }
}
