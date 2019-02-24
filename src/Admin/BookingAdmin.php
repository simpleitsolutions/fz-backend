<?php

namespace App\Admin;


use App\Entity\Passenger;
use App\Form\PassengerType;
use App\Repository\BookingOwnerRepository;
use App\Repository\MeetingLocationRepository;
use App\Repository\ProductRepository;
use DateTime;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;

use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class BookingAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'booking';

    protected $baseRouteName = 'booking';

    protected $searchResultActions = ['show'];

    public $supportsPreviewMode = true;

    public function getNewInstance()
    {
        $instance = parent::getNewInstance();
        $entityManager = $this->getModelManager()->getEntityManager('App\Entity\BookingOwner');
        $bookingOwner = $entityManager->getRepository('App\Entity\BookingOwner')->findOneBy(array('name' => 'FlyZermatt'));

        $instance->setOwner($bookingOwner);
        if ($this->hasRequest()) { //Pre-load new Booking from Booking Schedule view.
            $targetDate = $this->getRequest()->get('date', null);
            if($targetDate != null) {
                $instance->setFlightDate(DateTime::createFromFormat('Y-m-d', $targetDate));
            }

            $flightScheduleTimeId = $this->getRequest()->get('flightScheduleTimeId', null);
            if($flightScheduleTimeId != null) {
                $entityManager = $this->getModelManager()->getEntityManager('App\Entity\FlightScheduleTime');
                $flightScheduleTime = $entityManager->getRepository('App\Entity\FlightScheduleTime')->find($flightScheduleTimeId);
                $instance->setFlightScheduleTime($flightScheduleTime);
                $instance->setMeetingTime($flightScheduleTime->getScheduleStartTime());
            }
        }

//        $meetingLocation = $entityManager->getRepository('App\Entity\MeetingLocation')->find(3);
//        $flight = $entityManager->getRepository('App\Entity\Product')->find(1);
//
//        $instance->setStatus(1);
//        $instance->setContactInfo("0799610043");
////        $instance->setFlightDate("2019-03-02 00:00:00");
//        $instance->setMeetingTime(new \DateTime());
//        $instance->setFlight($flight);
//        $instance->setNotes("Some notes...");
        $instance->addPassenger(new Passenger());
//        $instance->setMeetingLocation($meetingLocation);

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
        $em = $this->modelManager->getEntityManager('App\Entity\Product');

        
        $formMapper
        ->with('Details', ['class' => 'col-md-4'])
            ->add('flightdate', DateType::class, [ 'label' => 'Flight Date',
                            'widget' => 'single_text',
//                            'html5' => false,
                            'attr' => ['class' => 'js-datepicker'],])
            ->add('meetingTime', TimeType::class)
            ->add('flight', null,
                        ['placeholder' => 'Please Select', 'attr'=>array('data-sonata-select2'=>'false'),
                         'query_builder' => function(ProductRepository $er) {
                                                return $er->createQueryBuilder('p')
                                                    ->join('p.productCategory', 'pc')
                                                    ->where('pc.name = :category')
                                                    ->setParameter('category', 'FLIGHT')
                                                    ->orderBy('p.sortOrder', 'ASC');
                                                } ])
            ->add('meetingLocation', null,
                            ['placeholder' => 'Please Select',
//                                'preferred_choices' => $options['preferredMeetingLocations'],
                                'query_builder' => function(MeetingLocationRepository $er) {
                                    return $er->createQueryBuilder('ml')
                                        ->orderBy('ml.sortOrder', 'ASC');}])
            ->add('contactinfo', null, ['label' => 'Contact'])
        ->end()
        ->with('Passengers', ['class' => 'col-md-8'])
            ->add('passengers',
                CollectionType::class,
                    ['label' => false,
                        'allow_add' => true,
                        'by_reference' => false,
                        'allow_delete' => true,
                        'prototype' => true,
                        'entry_type' => PassengerType::class,
                    ]
                )
        ->end()
        ->with('Notes', ['class' => 'col-md-9'])
            ->add('notes', TextareaType::class, ['label' => 'Comments', 'required' => false, 'attr' => ['rows' => '6']])
        ->end()
        ->with('Office', ['class' => 'col-md-3'])
            ->add('owner', null, ['placeholder' => false,
                    'multiple' => false,
                    'expanded' => true,
                    'query_builder' => function(BookingOwnerRepository $er) {
                        return $er->createQueryBuilder('bo')
                            ->orderBy('bo.sortOrder', 'ASC');}]
            )
//            ->add('created', DateType::class, ['widget' => 'single_text', 'disabled' => true])
//            ->add('createdBy', TextType::class, ['disabled' => true])
//            ->add('updated', DateType::class, ['widget' => 'single_text', 'disabled' => true])
//            ->add('lastUpdatedBy', TextType::class, ['disabled' => true] )
        ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id', null, array('global_search' => true))
            ->add('contactinfo')
//            ->add('flightdate', null, array('global_search' => true))
//            ->add('flight')
//            ->add('passengers')
//            ->add('meetingTime')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('status')
            ->add('contactinfo', null, ['label' => 'Contact Info'])
            ->add('flightdate', null, ['label' => 'Flight Date', 'format' => 'd.M.Y'])
            ->add('flight')
            ->add('passengers')
            ->add('meetingTime', null, ['format' => 'H:i'])
            ->add('meetingLocation')
            ->add('created', null, ['format' => 'd.M.Y H:i'])
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
            ->add('status')
            ->add('contactinfo')
            ->add('flightdate')
            ->add('flight')
            ->add('passengers')
            ->add('meetingTime')
            ->add('meetingLocation')
        ;
    }

    public function prePersist($object)
    {
        $user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
        $createdDate = new \DateTime();
        $object->setCreatedBy($user);
        $object->setLastUpdatedBy($user);
        $object->setCreated($createdDate);
        $object->setUpdated($createdDate);

        foreach ($object->getPassengers() as $passenger) {
            $passenger->setFlight($object->getFlight());
        }
    }

    public function __toString()
    {
        return "Booking";
    }
}
