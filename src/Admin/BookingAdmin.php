<?php

namespace App\Admin;


use App\Entity\MeetingLocation;
use App\Entity\Passenger;
use App\Form\PassengerType;
use App\Repository\BookingOwnerRepository;
use App\Repository\MeetingLocationRepository;
use App\Repository\ProductRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
//use Sonata\CoreBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Form\Type\CollectionType;
//use Sonata\Form\Type\CollectionType;

use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;
use Sonata\Form\Type\DateRangePickerType;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class BookingAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'booking';

    protected $baseRouteName = 'booking';

    public $supportsPreviewMode = true;

    public function getNewInstance()
    {
        $instance = parent::getNewInstance();

//        $entityManager = $this->getModelManager()->getEntityManager('App\Entity\MeetingLocation');
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
        ->with('Details', ['class' => 'col-md-5'])
//            ->add('id')
//            ->add('flightScheduleTime')
//              ->add('flightdate', DateTimePickerType::class, ['datepicker_use_button' => false] )
            ->add('flightdate', DateType::class, [ 'label' => 'Flight Date',
                            'widget' => 'single_text',
//                            'html5' => false,
                            'attr' => ['class' => 'js-datepicker'],])
            ->add('meetingTime', TimeType::class)
            ->add('flight', null,
                        ['placeholder' => 'Please Select',
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
            ->add('notes', null, ['label' => 'Comments'])
            ->add('owner', null, ['label' => false,
                    'multiple' => false,
                    'expanded' => true,
                    'query_builder' => function(BookingOwnerRepository $er) {
                        return $er->createQueryBuilder('bo')
                            ->orderBy('bo.sortOrder', 'ASC');}]
            )
            ->add('created', DateType::class, ['widget' => 'single_text', 'disabled' => true])
            ->add('createdBy', TextType::class, ['disabled' => true])
            ->add('updated', DateType::class, ['widget' => 'single_text', 'disabled' => true])
            ->add('lastUpdatedBy', TextType::class, ['disabled' => true] )
        ->end()
        ->with('Passengers', ['class' => 'col-md-7'])
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
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('contactinfo')
            ->add('flightdate')
            ->add('flight')
            ->add('passengers')
            ->add('meetingTime')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('created')
            ->add('status')
            ->add('contactinfo')
            ->add('flightdate')
            ->add('flight')
            ->add('passengers')
            ->add('meetingTime')
            ->add('meetingLocation')
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
    }

    public function __toString()
    {
        return "Booking";
    }
}
