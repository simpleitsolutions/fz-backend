<?php
namespace App\Form;

use App\Entity\Product;
use App\Entity\PurchaseItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BPPurchaseItemType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	// $builder->add('product', 'entity', array('class' => 'AazpBookingBundle:Product', 'property' => 'name', 'empty_value' => 'Please Select', 'empty_data' => null, 'required' => false));
        $builder->add('product', EntityType::class, array('label' => 'Flight', 'class' => Product::class, 'choice_label' => 'description', 'placeholder' => 'Please Select', 'required' => false, 'attr'=>array('class'=>'form-control', 'data-sonata-select2'=>'false')));
		// $builder->add('description');
    	$builder->add('amount', NumberType::class, array('scale' => 2));
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('data_class' => PurchaseItem::class, ));
		$resolver->setDefaults(['empty_data' => function (FormInterface $form) {
        		return new PurchaseItem($form->get('product')->getData());
			}
        ,]);
	}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => PurchaseItem::class,
            'empty_data' => function (FormInterface $form) {
                return new PurchaseItem($form->get('product')->getData());
            },
        ]
        );
    }


    public function getName()
    {
        return 'purchaseItem';
    }
}
