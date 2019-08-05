<?php
namespace App\Form;

use App\Entity\PaymentType;
use App\Entity\Purchase;
use App\Repository\PaymentTypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurchaseType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder->add('purchaseItems', CollectionType::class, array('entry_type' => PurchaseItemType::class, 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false));
        $builder->add('paymentType',
                        EntityType::class,
                            array('label' => 'Payment Type',
                                'class' => PaymentType::class,
                                'choice_label' => 'name',
                                'query_builder' => function(PaymentTypeRepository $er) {
                                    return $er->createQueryBuilder('pt')
                                        ->orderBy('pt.sortOrder', 'ASC');},
                                'expanded' => true));
        $builder->add('paymentAmount', NumberType::class, array('scale' => 2));
		$builder->add('sumupRef', TextType::class, array('mapped' => false, 'required' => false));
		$builder->add('description', TextType::class, array('mapped' => false, 'required' => false));
	}

//	public function setDefaultOptions(OptionsResolverInterface $resolver)
//    {
//        $resolver->setDefaults(array('data_class' => Purchase::class, ));
//	}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Purchase::class, ]
        );
    }

    public function getName()
    {
        return 'purchase';
    }
}
