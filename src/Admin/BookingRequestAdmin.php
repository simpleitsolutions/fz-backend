<?php
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\BooleanType;

class BookingRequestAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'bookingrequest';

    protected $baseRouteName = 'bookingrequest';

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
            ->with('Booking Request', array('class' => 'col-md-6'))
//            ->add('id')
            ->add('name')
            ->add('phone')
            ->add('email')
            ->add('noPassengers')
            ->add('flight')
            ->add('flightDate')
            ->add('arrivalDate')
            ->add('departureDate')
            ->add('groupConditions')
            ->add('comments')
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('confirmed')
            ->add('phone')
            ->add('email')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('confirmed', BooleanType::class, ['template' => '/bookingrequest/list/list__col_confirmed.html.twig'])
            ->add('phone')
            ->add('email')
            ->add('noPassengers')
            ->add('flight')
            ->add('flightDate', null, ['format' => 'd.m.y'])
            ->add('arrivalDate', null, ['format' => 'd.m.y'])
            ->add('departureDate', null, ['format' => 'd.m.y'])
            ->add('groupConditions')
            ->add('comments')
            ->add('_action', null, [
                'actions' => [
                    'edit' => ['template' => '/sonataadmin/CRUD/list__action_edit.html.twig'],
                    'delete' => ['template' => '/sonataadmin/CRUD/list__action_delete.html.twig'],
                    'confirm' => ['template' => '/bookingrequest/list/list__action_confirm.html.twig'],
                ]
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('phone')
            ->add('email')
            ->add('noPassengers')
            ->add('flight')
            ->add('flightDate')
            ->add('arrivalDate')
            ->add('departureDate')
            ->add('groupConditions')
            ->add('comments')
        ;
    }

    public function toString($object)
    {
        return $object->getName();
    }

}
