<?php
namespace App\Form;

use App\Entity\Product;
use App\Entity\PurchaseItem;
use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BPPurchaseItemType extends AbstractType
{
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	// $builder->add('product', 'entity', array('class' => 'AazpBookingBundle:Product', 'property' => 'name', 'empty_value' => 'Please Select', 'empty_data' => null, 'required' => false));
        $builder->add('product',
                    EntityType::class,
                            ['label' => 'Flight',
                                'class' => Product::class,
                                'choice_label' => 'description',
                                'placeholder' => 'Please Select',
                                'required' => false,
                                'empty_data' => null,
                                'preferred_choices' => $this->productRepository->getPreferredFlightProducts(),
                                'group_by' => function($choice, $key, $value) {
                                    if ($choice->getProductCategory() == "FLIGHT") {
                                        return 'Flight';
                                    } else if($choice->getProductCategory() == "TRANSPORT") {
                                        return 'Transport';
                                    } else {
                                        return 'Other';
                                    }
                                },
                                'query_builder' => function (ProductRepository $er) {
                                    return $er->createQueryBuilder('p')
                                        ->join('p.productCategory', 'pc')
                                        ->where('pc.name NOT IN(:categories)')
                                        ->setParameter('categories', ['MISC','VOUCHER'])
                                        ->orderBy('p.sortOrder', 'ASC');
                                }
                            ]);
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
