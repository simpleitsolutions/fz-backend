<?php
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class WaitingListAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'waitinglist';

    protected $baseRouteName = 'waitinglist';

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
            ->with('Waiting List', array('class' => 'col-md-4'))
//            ->add('id')
            ->add('waitingListItemDate')
            ->add('name')
            ->add('contactinfo')
            ->add('noofpassengers')
            ->add('notes')
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('waitingListItemDate')
            ->add('name')
            ->add('contactinfo')
            ->add('noofpassengers')
            ->add('notes')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('waitingListItemDate')
            ->add('name')
            ->add('contactinfo')
            ->add('noofpassengers')
            ->add('notes')
            ->add('_action', null, array(
                'actions' => array(
                    'edit' => [],
                    'delete' => [],
                )
            ))
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('waitingListItemDate')
            ->add('name')
            ->add('contactinfo')
            ->add('noofpassengers')
            ->add('notes')
        ;
    }

}
