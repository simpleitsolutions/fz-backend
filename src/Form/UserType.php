<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username');
        $builder->add('email');
        $builder->add('plainTextPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => is_null($builder->getData()->getId()),
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Password Confirm'],
            ]);
//        $builder->add('roles', CollectionType::class, ['entry_type' => ChoiceType::class, 'entry_options' => ['label' => false, 'choices' => ['ROLE_USER'=>'ROLE_USER', 'ROLE_ADMIN'=>'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'=>'ROLE_SUPER_ADMIN']]]);
        $builder->add('roles', ChoiceType::class, ['label' => false, 'multiple' => true, 'choices' => ['ROLE_USER'=>'ROLE_USER', 'ROLE_ADMIN'=>'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'=>'ROLE_SUPER_ADMIN']]);
        $builder->add('pilot');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => User::class, ]);
    }

    public function __toString()
    {
        return User::class;
    }
}
