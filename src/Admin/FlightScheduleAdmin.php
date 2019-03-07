<?php

namespace App\Admin;


use App\Entity\FlightSchedule;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class FlightScheduleAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'flight_schedule';

    protected $baseRouteName = 'flight_schedule';

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
        '_sort_by' => 'id',
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Flight Schedule', array('class' => 'col-md-6'))
//            ->add('id')
            ->add('affectiveStartDate')
            ->add('affectiveEndDate')
            ->add('flightScheduleTimes')
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('affectiveStartDate')
            ->add('affectiveEndDate')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('affectiveStartDate')
            ->add('affectiveEndDate')
            ->add('flightScheduleTimes')
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
            ->add('affectiveStartDate')
            ->add('affectiveEndDate')
            ->add('flightScheduleTimes')
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
        return "[".$object->getAffectiveStartDate()->format('d.m.Y')."-".$object->getAffectiveEndDate()->format('d.m.Y')."]";
    }
}
