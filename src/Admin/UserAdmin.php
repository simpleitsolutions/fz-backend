<?php

namespace App\Admin;


use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class UserAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'user';

    protected $baseRouteName = 'user';

    protected $searchResultActions = ['show'];

    public function getNewInstance()
    {
        $instance = parent::getNewInstance();

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
        $formMapper
            ->with('User', array('class' => 'col-md-3'))
//            ->add('id')
            ->add('username')
            ->add('email')
            ->add('password')
            ->add('roles', CollectionType::class, ['entry_type' => ChoiceType::class, 'entry_options' => ['label' => false, 'choices' => ['ROLE_USER'=>'ROLE_USER', 'ROLE_ADMIN'=>'ROLE_ADMIN', 'ROLE_SUPER_ADMIN'=>'ROLE_SUPER_ADMIN']]])
            ->add('pilot')
//            ->add('plainTextPassword', RepeatedType::class)
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            ->add('email')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('username')
            ->add('email')
            ->add('pilot')
//            ->add('roles')
            ->add('_action', null, array(
                'actions' => array(
                    'edit' => ['template' => '/user/list/list__action_edit.html.twig'],
                    'delete' => ['template' => '/sonataadmin/CRUD/list__action_delete.html.twig'],
                )
            ))
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('username')
            ->add('email')
            ->add('pilot')
        ;
    }

    public function configureBatchActions($actions)
    {
        if (isset($actions['delete'])) {
            unset($actions['delete']);
        }

        return $actions;
    }

    public function toString($object)
    {
        return $object instanceof User
            ? $object->getUsername()
            : 'User';
    }
}
