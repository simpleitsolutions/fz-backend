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
use Doctrine\ORM\EntityRepository;

class PurchaseItemType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	// $builder->add('product', 'entity', array('class' => 'AazpBookingBundle:Product', 'property' => 'name', 'empty_value' => 'Please Select', 'empty_data' => null, 'required' => false));
        $builder->add('product', EntityType::class, array('label' => 'Flight', 'group_by' => 'productCategory.name', 'class' => Product::class,'choice_label' => 'description', 'placeholder' => 'Please Select', 'required' => false,
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('p')
                                ->orderBy('p.sortOrder', 'ASC');
                        }, ));
		// $builder->add('description');
    	$builder->add('amount', NumberType::class, ['scale' => 2]);
    }

//	public function setDefaultOptions(OptionsResolverInterface $resolver)
//    {
//        $resolver->setDefaults(array('data_class' => 'Aazp\BookingBundle\Entity\PurchaseItem', ));
//		$resolver->setDefaults(['empty_data' => function (FormInterface $form) {
//        		return new PurchaseItem($form->get('product')->getData());
//			}
//        ,]);
//	}

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
