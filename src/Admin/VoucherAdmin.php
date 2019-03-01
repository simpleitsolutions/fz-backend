<?php
namespace App\Admin;

use App\Entity\Product;
use App\Entity\Voucher;
use App\Repository\ProductRepository;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class VoucherAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'voucher';

    protected $baseRouteName = 'voucher';

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
            ->with('Voucher', array('class' => 'col-md-4'));
//            ->add('id')

        $formMapper->getFormBuilder()->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $voucher = $event->getData();
            $form = $event->getForm();
            if($voucher && null != $voucher->getId()) {
                $form->add('id', null, array('disabled'=> true)); //TODO: Need to have this loaded as the first field in the form. Currently it is the last.
            }
        });

        $formMapper->add('name')
            ->add('flight', EntityType::class, ['label' => 'Flight',
                                                            'class' => Product::class,
                                                            'choice_label' => 'description',
                                                            'placeholder' => 'Please Select',
                                                            'query_builder' => function(ProductRepository $er) {
                                                                    return $er->createQueryBuilder('p')->join('p.productCategory', 'pc')->where('pc.name = :category')->setParameter('category', 'FLIGHT')->orderBy('p.sortOrder', 'ASC');}
                                                                    ])
            ->add('withPhotos', ChoiceType::class, ['label' => 'Photos', 'choices' => array('NO'=>0, 'YES'=>1), 'multiple' => false, 'expanded' => true])
            ->add('message', TextareaType::class, ['required' => false, 'attr' => ['rows' => '4']])
            ->add('language', ChoiceType::class, ['expanded' => true, 'choices' => ['English' => Voucher::ENGLISH, 'German' => Voucher::GERMAN]])
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id', null, array('global_search' => true))
            ->add('name')
            ->add('flight')
            ->add('language')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('name')
            ->add('flight')
            ->add('withPhotos', null, ['label' => 'Photos'])
            ->add('language', null, ['template' => 'voucher/list__language.html.twig'])
            ->add('created', null, ['format' => 'd.m.Y H:i'])
            ->add('flightdate', null, ['label' => 'Flight Date', 'format' => 'd.m.Y H:i'])
            ->add('_action', null, array(
                'actions' => array(
                    'show' => ['template' => 'voucher/list__action_show.html.twig'],
                    'edit' => ['template' => '/sonataadmin/CRUD/list__action_edit.html.twig'],
                    'delete' => ['template' => '/sonataadmin/CRUD/list__action_delete.html.twig'],
                    'redeem' => ['template' => 'voucher/list__action_redeem.html.twig']
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

//    protected function configureBatchActions($actions)
//    {
//        $actions['voucher_custom_redeem'] = ['ask_confirmation' => true];
//
//        return $actions;
//    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->add('redeem', 'redeem/'.$this->getRouterIdParameter());
    }

    public function toString($object)
    {
        return $object->getName();
    }
}
