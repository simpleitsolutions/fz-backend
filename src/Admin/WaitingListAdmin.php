<?php
namespace App\Admin;

use DateTime;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class WaitingListAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'waitinglist';

    protected $baseRouteName = 'waitinglist';

    protected $searchResultActions = ['show'];

    public function getNewInstance()
    {
        $instance = parent::getNewInstance();

        if ($this->hasRequest()) {
            $targetDate = $this->getRequest()->get('date', null);
            $instance->setWaitingListItemDate(DateTime::createFromFormat('Y-m-d', $targetDate));
        }

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
            ->add('waitingListItemDate', DateType::class, [
                                'widget' => 'single_text',
//                            'html5' => false,
                                'attr' => ['class' => 'js-datepicker'],])
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
            ->add('name')
            ->add('contactinfo')
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
                    'edit' => ['template' => '/sonataadmin/CRUD/list__action_edit.html.twig'],
                    'delete' => ['template' => '/sonataadmin/CRUD/list__action_delete.html.twig'],
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
