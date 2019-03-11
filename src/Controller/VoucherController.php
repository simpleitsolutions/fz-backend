<?php
namespace App\Controller;

use App\Entity\Voucher;
use App\Entity\Payment;
use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\ProductCategory;
use App\Form\PurchaseType;
use App\Entity\PurchaseItem;
use App\Helper\PaymentViewHelper;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/voucher")
 */
class VoucherController extends AbstractController
{
//    public function indexAction()
//	{
//	   	$request = Request::createFromGlobals();
//
//        $em = $this->getDoctrine()->getManager();
//		$dql   = "SELECT v FROM AazpVoucherBundle:Voucher v ORDER BY v.id DESC";
//		$query = $em->createQuery($dql);
//
//		$paginator  = $this->get('knp_paginator');
//    	$pagination = $paginator->paginate(
//	        $query,
//	        $this->get('request')->query->get('page', 1)/*page number*/,
//	        10/*limit per page*/
//	    );
//
//        return $this->render('AazpVoucherBundle:Voucher:index.html.twig', array('pagination' => $pagination));
//	}
//
//    public function newAction(Request $request)
//	{
//	   	$request = Request::createFromGlobals();
//		$voucher = new Voucher();
//
//		$form = $this->createForm(new VoucherType(), $voucher);
//		$form->add('save', 'submit');
//		$form->add('saveAndExit', 'submit', array('label' => 'Save'));
// 		$form->add('cancel', 'submit', array('attr' => array('formnovalidate' => true, 'data-toggle' => 'modal', 'data-target' => '#cancelWarning', )));
//
//		$form->handleRequest($request);
//
//		if($form->get('cancel')->isClicked())
//		{
//			return $this->redirect($this->generateUrl('voucher_index'));
//		}
//
//	    if ($form->isValid()) {
//	    	$em = $this->getDoctrine()->getManager();
//		    $em->persist($voucher);
//		    $em->flush();
//
//			$this->addFlash('sonata_flash_success','New Voucher has been successfully created!');
//
//			$nextAction = $form->get('saveAndExit')->isClicked()
//			        ? $this->generateUrl('voucher_show', array('id' => $voucher->getId()))
//			        : $this->generateUrl('voucher_new');
//	        return $this->redirect($nextAction);
//	    }
//		return $this->render('AazpVoucherBundle:Voucher:new.html.twig', array('form' => $form->createView()));
//	}
//
//    public function updateAction($id)
//	{
//    	$request = $this->get('request');
//
//        $em = $this->getDoctrine()->getManager();
//        $voucher = $em->getRepository('AazpVoucherBundle:Voucher')->find($id);
//
//        if (!$voucher) {
//            throw $this->createNotFoundException('Unable to find Voucher entity.');
//        }
//
//		$form = $this->createForm(new VoucherType(), $voucher);
//		$form->add('save', 'submit');
//		$form->add('saveAndExit', 'submit', array('label' => 'Save'));
// 		$form->add('cancel', 'submit', array('attr' => array('formnovalidate' => true, 'data-toggle' => 'modal', 'data-target' => '#cancelWarning', )));
//
//		$form->handleRequest($request);
//
//		if($form->get('cancel')->isClicked())
//		{
//			return $this->redirect($this->generateUrl('voucher_index'));
//		}
//
//        if ($form->isValid()) {
//
//            $em->persist($voucher);
//            $em->flush();
//
//			$this->addFlash('sonata_flash_success','Voucher has been successfully updated!');
//
//			$nextAction = $form->get('saveAndExit')->isClicked()
//			        ? $this->generateUrl('voucher_show', array('id' => $id))
//			        : $this->generateUrl('voucher_update', array ('id'=> $id));
//	        return $this->redirect($nextAction);
//        }
//		return $this->render('AazpVoucherBundle:Voucher:edit.html.twig', array(
//            'form'   => $form->createView(),
//		));
//	}

//    /**
//     * @Route("/redeem/{id}", name="voucher_custom_redeem")
//     */
//    public function redeemAction($id)
//	{
//        $request = Request::createFromGlobals();
////		$request = $this->get('request');
//
//        $em = $this->getDoctrine()->getManager();
//        $voucher = $em->getRepository(Voucher::class)->find($id);
//
//        if (!$voucher)
//        {
//            throw $this->createNotFoundException('Unable to find Voucher entity.');
//        }
//
//		$form = $this->createFormBuilder($voucher)
//			->add('redeem', SubmitType::class, array('label' => 'Redeem'))
//			->add('cancel', SubmitType::class, array('label' => 'Cancel'))
//        	->getForm();
//
//		$form->handleRequest($request);
//
//		if($form->get('cancel')->isClicked())
//		{
//			return $this->redirect($this->generateUrl('voucher_index'));
//		}
//		else if($form->get('redeem')->isClicked())
//		{
//			$voucher->setFlightdate(new \DateTime);
//            $em->persist($voucher);
//            $em->flush();
//
//			$this->addFlash('sonata_flash_success','Voucher has been successfully redeemed!');
//			return $this->redirect($this->generateUrl('voucher_index'));
//		}
//        return $this->redirect($this->generateUrl('app_voucher_list'));
////		return $this->forward($this->ge'));
////		return $this->render('AazpVoucherBundle:Voucher:redeem.html.twig', array('form' => $form->createView()));
//	}

    /**
     * @Route("/show/{id}", name="voucher_custom_show")
     */
    public function showAction($id)
	{
    	$request = Request::createFromGlobals();
//		$report = $request->query->get('report', false);

        $em = $this->getDoctrine()->getManager();
        $voucher = $em->getRepository(Voucher::class)->find($id);

        if (!$voucher)
        {
            throw $this->createNotFoundException('Unable to find Voucher entity.');
        }

		if($voucher->getLanguage() == Voucher::GERMAN)
		{
			return $this->render('voucher/show.de.html.twig', array('voucher' => $voucher));
		} else if($voucher->getLanguage() == Voucher::ENGLISH)
        {
			return $this->render('voucher/show.html.twig', array('voucher' => $voucher));
		}
		
    }

	public function reportGiftVoucherAction($id)
	{
        $em = $this->getDoctrine()->getManager();
        $voucher = $em->getRepository('AazpVoucherBundle:Voucher')->find($id);

        if (!$voucher)
        {
            throw $this->createNotFoundException('Unable to find Voucher entity.');
        }
		
		$html = $this->renderView('AazpVoucherBundle:Report:gift.voucher.html.twig', array('voucher' => $voucher, 'report' => 'true'));

        return new Response(
            $this->container->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'attachment; filename="gift-voucher-'.$voucher->getId().'-'.$voucher->getName().'.pdf"'
            )
        );  
	}

    /**
     * @Route("/payment/{id}", name="voucher_custom_payment")
     */
    public function paymentAction($id)
    {
        $request = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $paymentsRepos = $em->getRepository(Payment::class);

        $voucher = $em->getRepository(Voucher::class)->find($id);
        if (!$voucher)
        {
            throw $this->createNotFoundException('Unable to find Voucher entity.');
        }

        $purchase = $voucher->getPurchase();
        $payments = [];
        if($purchase === NULL)
        {
            $purchase = new Purchase();

            $purchaseItemFlight = new PurchaseItem($voucher->getFlight());
            $purchase->addPurchaseItem($purchaseItemFlight);
//            throw new \Exception("HERE  ".$voucher->getWithPhotos());

            if($voucher->getWithPhotos())
            {
                $productCategoryRepos = $em->getRepository(ProductCategory::class);
                $productRepos = $em->getRepository(Product::class);
                $productCategoryPhoto = $productCategoryRepos->findOneByName('FLIGHT-PHOTO');
                $productPhotoOption = $productRepos->findOneByProductCategory($productCategoryPhoto);
                if (!$productPhotoOption)
                {
                    throw $this->createNotFoundException('Unable to find Flight Photo Product entity.');
                }
                $purchaseItemPhoto = new PurchaseItem($productPhotoOption);
                $purchase->addPurchaseItem($purchaseItemPhoto);
            }
            $voucher->setPurchase($purchase);
        } else
        {
            //LOAD PAYMENT LIST
            $payments = $paymentsRepos->getPaymentsForVoucher($voucher);
        }
        $paymentViewHelper = new PaymentViewHelper();
        $paymentViewList = $paymentViewHelper->processVoucherPayments($payments);

        $purchase->setPaymentAmount($voucher->calculateOwing());

        $form = $this->createForm(PurchaseType::class, $purchase);
        $form->add('pay', SubmitType::class, ['attr' => ['class' => 'btn btn-success']]);
        $form->add('cancel', ButtonType::class,
            ['attr' => ['class' => 'btn',
                'formnovalidate' => true,
                'data-toggle' => 'modal',
                'data-target' => '#modalWarning',
                'data-href' => $this->generateUrl('voucher_custom_show', array ('id'=> $voucher->getId())),
                'data-modal-title' => 'Are you want to cancel this payment?']]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $paymentAmount = $form->get('paymentAmount')->getData();
            $paymentType = $form->get('paymentType')->getData();
            $paymentDescription = $form->get('description')->getData();
            $paymentSumupRef = $form->get('sumupRef')->getData();
            $transactionNo = date('YmdHis');

            $payment = new Payment();
            $payment->setAmount($paymentAmount);
            $payment->setSubAmount(null);
            $payment->setPaymentType($paymentType);
            $payment->setDescription($paymentDescription);
            $payment->setSumupRef($paymentSumupRef);
            $payment->setTransactionNo($transactionNo);

            $purchase = $voucher->getPurchase();
            if($purchase === null)
            {
                $purchase = new Purchase();
            }
            $payment->addPurchase($purchase);
            $purchase->addPayment($payment);

//            throw new \Exception("HERE ".$voucher->calculateOwing(). " '");
            if($voucher->calculateOwing() == 0)
            {
                $voucher->setStatus(Voucher::STATUS_PAYMENT_FULL);
            }
            else
            {
                $voucher->setStatus(Voucher::STATUS_PAYMENT_PART);
            }

            $paymentMessage = 'Payment '.number_format($paymentAmount,2).' CHF successful!';
            $messageType = 'sonata_flash_success';

//            if($voucher->paidInFull())
//            {
//                $voucher->setStatus(Booking::STATUS_PAYMENT_FULL);
//            }
//            else
//            {
//                $voucher->setStatus(Booking::STATUS_PAYMENT_PART);
//            }

            $em->persist($voucher);
            $em->flush();
            $this->addFlash($messageType, $paymentMessage);

            return $this->redirect($this->generateUrl('voucher_custom_show', array ('id'=> $voucher->getId())));
        }

        return $this->render('payment/voucher.payment.summary.html.twig', array(
            'paymentViewList' => $paymentViewList,
            'payments' => $payments,
            'voucher' => $voucher,
            'form'	=> $form->createView(),
        ));

    }

}
