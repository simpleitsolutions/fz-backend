<?php
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class VoucherAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'voucher';

    protected $baseRouteName = 'voucher';

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
            ->with('Voucher', array('class' => 'col-md-4'))
//            ->add('id')
            ->add('id', null, array('disabled'=> true))
            ->add('name')
            ->add('flight')
            ->add('withPhotos')
            ->add('language')
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('flight')
            ->add('withPhotos')
            ->add('language')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('name')
            ->add('flight')
            ->add('withPhotos')
            ->add('language')
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
            ->add('id')
            ->add('name')
            ->add('flight')
            ->add('withPhotos')
            ->add('language')
        ;
    }

}
