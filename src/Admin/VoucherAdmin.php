<?php
namespace App\Admin;

use App\Entity\Product;
use App\Entity\Voucher;
use App\Repository\ProductRepository;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
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
        $instance->setStatus(Voucher::STATUS_NEW);

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
            ->with('Personal', ['class' => 'col-md-4']);
//            ->add('id')

        $formMapper->getFormBuilder()->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $voucher = $event->getData();
            $form = $event->getForm();
            if($voucher && null != $voucher->getId()) {
                $form->add('id', null, ['disabled'=> true]); //TODO: Need to have this loaded as the first field in the form. Currently it is the last.
            }
        });

        $formMapper->add('name')
            ->add('flight',
                    EntityType::class,
                        ['label' => 'Flight',
                        'class' => Product::class,
                        'choice_label' => 'description',
                        'placeholder' => 'Please Select',
//                        'preferred_choices' => $this->productRepository->getPreferredFlightProducts(),
                        'query_builder' => function (ProductRepository $er) {
                            return $er->createQueryBuilder('p')
                                ->join('p.productCategory', 'pc')
                                ->where('pc.name IN(:categories)')
                                ->setParameter('categories', ['FLIGHT','VOUCHER'])
                                ->orderBy('p.sortOrder', 'ASC');
                        }]
            )
            ->add('message', TextareaType::class, ['required' => false, 'attr' => ['rows' => '4']])
        ->end()
        ->with('Voucher', ['class' => 'col-md-4'])
            ->add('withPhotos', ChoiceType::class, ['label' => 'Photos', 'choices' => ['NO'=>0, 'YES'=>1], 'multiple' => false, 'expanded' => true])
            ->add('language', ChoiceType::class, ['expanded' => true, 'choices' => ['English' => Voucher::ENGLISH, 'German' => Voucher::GERMAN]])
        ->end()
        ->with('Office Use', ['class' => 'col-md-4'])
            ->add('notes', TextareaType::class, ['required' => false, 'attr' => ['rows' => '6']])
        ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id', null, array('global_search' => true))
            ->add('status')
            ->add('name')
            ->add('flight')
            ->add('language')
            ->add('purchase')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('status', null, ['template' => '/voucher/list/list__col_status.html.twig'])
            ->add('name')
            ->add('flight')
            ->add('withPhotos', null, ['label' => 'Photos'])
            ->add('language', null, ['template' => '/voucher/list/list__language.html.twig'])
            ->add('created', null, ['format' => 'd.m.Y H:i'])
            ->add('notes')
            ->add('flightdate', null, ['label' => 'Flight Date', 'format' => 'd.m.Y H:i'])
            ->add('purchase')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => ['template' => '/voucher/list/list__action_show.html.twig'],
                    'edit' => ['template' => '/sonataadmin/CRUD/list__action_edit.html.twig'],
                    'delete' => ['template' => '/sonataadmin/CRUD/list__action_delete.html.twig'],
                    'redeem' => ['template' => '/voucher/list/list__action_redeem.html.twig'],
                    'payment' => ['template' => '/voucher/list/list__action_makepayment.html.twig']
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

    public function configureBatchActions($actions)
    {
        if (isset($actions['delete'])) {
            unset($actions['delete']);
        }

        return $actions;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->add('redeem', 'redeem/'.$this->getRouterIdParameter());
    }

//    protected function configureTabMenu(ItemInterface $menu, $action, AdminInterface $childAdmin = null)
//    {
//        // ...other tab menu stuff
//
//        $menu->addChild('comments', array('attributes' => array('dropdown' => true)));
//        $menu['comments']->addChild('list', ['uri' => $this->generateUrl('list'  )]);
//    }

    public function toString($object)
    {
        return $object instanceof Voucher
            ? $object->getId() ? "Voucher No.".$object->getId()." ".$object->getName():"Voucher"
            : 'Voucher';
    }
}
