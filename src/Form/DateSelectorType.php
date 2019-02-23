<?php
namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Repository\ProductRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;

class DateSelectorType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('targetDate', TextType::class, [
        'required' => true,
        'label' => 'Date',
        'attr' => array(
//            'class' => 'form-control input-inline datetimepicker',
//            'data-provide' => 'datepicker',
        )]);

//        $builder->add('name', TextType::class, array('label' => false, 'error_bubbling' => false) );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null)
        );
    }

    public function getName()
    {
        return 'passenger';
    }
}
