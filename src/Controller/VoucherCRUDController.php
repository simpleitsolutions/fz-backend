<?php
namespace App\Controller;

use App\Entity\Voucher;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class VoucherCRUDController extends CRUDController
{
    public function redeemAction($id)
    {
        $request = Request::createFromGlobals();

        $em = $this->getDoctrine()->getManager();
        $voucher = $em->getRepository(Voucher::class)->find($id);

        if (!$voucher)
        {
            throw $this->createNotFoundException('Unable to find Voucher entity.');
        }

        $voucher->setFlightdate(new \DateTime);
        $em->persist($voucher);
        $em->flush();

        $this->addFlash('sonata_flash_message','Voucher has been successfully redeemed!');


//        $form = $this->createFormBuilder($voucher)
//            ->add('redeem', SubmitType::class, array('label' => 'Redeem'))
//            ->add('cancel', SubmitType::class, array('label' => 'Cancel'))
//            ->getForm();
//
//        $form->handleRequest($request);
//
//        if($form->get('cancel')->isClicked())
//        {
//            return $this->redirect($this->generateUrl('voucher_index'));
//        }
//        else if($form->get('redeem')->isClicked())
//        {
//            $voucher->setFlightdate(new \DateTime);
//            $em->persist($voucher);
//            $em->flush();
//
//            $this->addFlash('sonata_flash_message','Voucher has been successfully redeemed!');
//            return $this->redirect($this->generateUrl('voucher_index'));
//        }
        return new RedirectResponse($this->admin->generateUrl('list'));
//		return $this->forward($this->ge'));
//		return $this->render('AazpVoucherBundle:Voucher:redeem.html.twig', array('form' => $form->createView()));
    }

}
