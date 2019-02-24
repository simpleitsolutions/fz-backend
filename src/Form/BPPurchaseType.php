<?php
namespace App\Form;

use App\Entity\Purchase;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BPPurchaseType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder->add('purchaseItems', CollectionType::class, array('entry_type' => BPPurchaseItemType::class, 'label' => false, 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false));
		// $builder->add('paymentType', 'entity', array('label' => 'Payment Type', 'class' => 'AazpBookingBundle:PaymentType','property' => 'name', 'expanded' => true));
		// $builder->add('paymentAmount', 'number', array('precision' => 2));
		// $builder->add('description', 'text', array('mapped' => false, 'required' => false));
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => Purchase::class, ));
	}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => Purchase::class)
        );
    }

    public function getName()
    {
        return 'purchase';
    }
}
