<?php
namespace App\Form;

use App\Entity\Booking;
use App\Entity\FlightScheduleTime;
use App\Entity\MeetingLocation;
use App\Entity\Product;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\MeetingLocationRepository;
use App\Repository\ProductRepository;
use App\Form\DataTransformer\TimeTransformer;
use App\Form\DataTransformer\DateTransformer;
use App\Repository\BookingOwnerRepository;
use App\Entity\BookingOwner;

class BookingType extends AbstractType
{
    protected $flightTimes;
    protected $preferredFlights;
    protected $preferredMeetingLocations;

//    public function __construct ( $flightTimes, $preferredFlights, $preferredMeetingLocations)
    public function __construct ()
    {
//        $this->flightTimes = $flightTimes;
//        $this->preferredFlights = $preferredFlights;
//        $this->preferredMeetingLocations = $preferredMeetingLocations;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder->add('flightdate', TextType::class, array(
		    'required' => true,
		    'label' => 'Date',
//		    'translation_domain' => 'AazpBookingBundle',
		    'attr' => array(
//		        'class' => 'form-control input-inline',
//		        'data-provide' => 'datepicker',
// 		        'data-format' => 'dd-MM-yyyy HH:mm',
		        'data-format' => 'dd-MM-yyyy',
		    )));

		$builder->add('meetingTime', TimeType::class, array(
		    'required' => true,
		    'label' => 'Meeting Time',
//		    'translation_domain' => 'AazpBookingBundle',
// 		    'required' => false,
//            'attr' => ['class' => 'form-control', 'data-sonata-select2' => 'false'],
//		        'class' => 'form-control input-inline',
//		        'data-provide' => 'datepicker',
//		        'data-format' => 'HH:mm',
		    ));
		
		$builder->add('flight', EntityType::class, array('label' => 'Flight',
		                                        'class' => Product::class,
                                                'attr' => ['class' => 'form-control', 'data-sonata-select2' => 'false'],
//		                                        'property' => 'name',
		                                        'placeholder' => 'Please Select',
		                                        'preferred_choices' => $options['preferredFlights'],
												'query_builder' => function(ProductRepository $er) {
		                                              return $er->createQueryBuilder('p')
		                                                      ->join('p.productCategory', 'pc')
		                                                      ->where('pc.name = :category')
		                                                      ->setParameter('category', 'FLIGHT')
		                                                      ->orderBy('p.sortOrder', 'ASC');
												            } ) );

		$builder->add('flightScheduleTime', EntityType::class, array(
//		    'empty_value' => 'Please Select',
		    'class' => FlightScheduleTime::class,
		    'label' => 'Schedule',
		    'required' => false,
		    'choices' => $options['flightScheduleTimes'],
		));
		
		$builder->add('passengers', CollectionType::class, array('entry_type' => PassengerType::class, 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false));
		$builder->add('contactinfo', TextareaType::class, array('label' => 'Contact', 'attr' => ['class' => 'form-control']));
//        $builder->add('meetinglocation', ModelType::class, ['label' => 'Meeting Location', 'class' => MeetingLocation::class,]);

		$builder->add('meetinglocation', EntityType::class, array('label' => 'Meeting Location',
		                                                 'class' => MeetingLocation::class,
//		                                                 'property' => 'name',
                                                         'attr' => ['class' => 'form-control', 'data-sonata-select2' => 'false'],
		                                                 'placeholder' => 'Please Select',
		                                                 'preferred_choices' => $options['preferredMeetingLocations'],
            											 'query_builder' => function(MeetingLocationRepository $er) {
            											        return $er->createQueryBuilder('ml')
            											                 ->orderBy('ml.sortOrder', 'ASC');} ) );

		$builder->add('owner', EntityType::class, array('label' => false,
		    'class' => BookingOwner::class,
//		    'property' => 'name',
		    'multiple' => false,
		    'expanded' => true,
		    'query_builder' => function(BookingOwnerRepository $er) {
		              return $er->createQueryBuilder('bo')
		                        ->orderBy('bo.sortOrder', 'ASC');}
            ) );
		
		$builder->add('notes', TextareaType::class, array('label' => 'Comments', 'required' => false, 'attr' => ['class' => 'form-control']));

		$builder->get('flightdate')->addModelTransformer(new DateTransformer());
//		$builder->get('meetingTime')->addModelTransformer(new TimeTransformer());
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Booking::class, 'cascade_validation' => true, 'validation_groups' => array('Booking', 'quick'),));
	}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'flightScheduleTimes' => null,
            'preferredFlights' => null,
            'preferredMeetingLocations' => null,
        ));
    }

    public function getName()
    {
        return 'booking';
    }
}
