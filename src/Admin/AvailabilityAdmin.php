<?php

namespace App\Admin;


use App\Entity\FlightScheduleTime;
use App\Repository\FlightScheduleTimeRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AvailabilityAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'availability';

    protected $baseRouteName = 'availability';

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
        // 			'_sort_by' => 'updatedAt',
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->with('User', array('class' => 'col-md-6'));

        if ($this->isCurrentRoute('edit')) {
            $formMapper->add('unavailableFlightDate', null, ['disabled'=> true]);
            $formMapper->add('pilot', null, ['disabled'=> true]);
        }
        else
        {
            $formMapper->add('unavailableFlightDate');
            $formMapper->add('pilot');
        }

        $formMapper->add('flightScheduleTimes', null, [
            'expanded' => true,
            'class' => FlightScheduleTime::class,
            'query_builder' => function(FlightScheduleTimeRepository $er) {
                return $er->createQueryBuilder('ft')
                    ->join('ft.flightSchedule', 'fs')
                    ->where('fs.affectiveStartDate <= :now')
                    ->andWhere('fs.affectiveEndDate >= :now')
                    ->setParameter('now', $this->subject->getUnavailableFlightDate()->format("Y-m-d"))
                    ->orderBy('ft.scheduleStartTime', 'ASC');}
        ]);

        $formMapper->end();

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('unavailableFlightDate')
            ->add('pilot')
            ->add('flightScheduleTimes')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('unavailableFlightDate', null, ['label' => 'Date'])
            ->add('pilot.name')
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
            ->add('unavailableFlightDate')
            ->add('pilot')
            ->add('flightScheduleTimes')
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        if($user->getPilot())
        {
            $query->andWhere($query->expr()->eq($query->getRootAliases()[0] . '.pilot', ':pilot'));
            $query->setParameter('pilot', $user->getPilot());
        }

        return $query;

    }

}
