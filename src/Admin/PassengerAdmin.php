<?php
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PassengerAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'passenger';

    protected $baseRouteName = 'passenger';

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
//            ->add('id')
            ->add('name')
//            ->add('flight')
//            ->add('booking')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, array('global_search' => true))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->addIdentifier('booking')
//            ->add('_action', null, array(
//                'actions' => array(
//                    'edit' => ['template' => '/sonataadmin/CRUD/list__action_edit.html.twig'],
//                    'delete' => ['template' => '/sonataadmin/CRUD/list__action_delete.html.twig'],
//                )
//            ))
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('booking')
        ;
    }

}
