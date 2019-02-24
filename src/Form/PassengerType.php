<?php
namespace App\Form;

use App\Entity\Passenger;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Repository\ProductRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;

class PassengerType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array('label' => false, 'error_bubbling' => false) );

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $passenger = $event->getData();
            $form = $event->getForm();

            // checks if the Passenger object is "new"
            // If no data is passed to the form, the data is "null".
            // This should be considered a new "Passenger"
            if ($passenger && null != $passenger->getId()) {

                $form->add('pilot',
                    EntityType::class,
                        array('label' => 'Pilot',
                            'class' => 'App\Entity\Pilot',
//                                'property' => 'name',
//                                'empty_value' => 'Please Select',
                                'empty_data' => null,
                                'required' => false));

                $form->add('flight',
                    EntityType::class,
                        array('label' => 'Flight',
                                'class' => 'App\Entity\Product',
//                    'property' => 'name',
//                    'empty_value' => 'Please Select',
                                'empty_data' => null,
                                'required' => false,
                                'query_builder' => function (ProductRepository $er) {
                                    return $er->createQueryBuilder('p')
                                            ->join('p.productCategory', 'pc')
                                            ->where('pc.name = :category')
                                            ->setParameter('category', 'FLIGHT')
                                            ->orderBy('p.sortOrder', 'ASC');
                    }));
            }

        });
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Passenger::class));
	}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Passenger::class)
        );
    }

    public function getName()
    {
        return 'passenger';
    }
}
