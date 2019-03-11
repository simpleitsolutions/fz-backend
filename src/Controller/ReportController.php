<?php
namespace App\Controller;


use App\Entity\Payment;
use App\Entity\PaymentType;
use App\Helper\EndOfDayReport;
use App\Helper\EndOfDayReportPilot;
use App\Helper\EndOfDayReportPilotBooking;
use App\Helper\PaymentViewHelper;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/report")
 */
class ReportController extends AbstractController
{

    /**
     * @Route("/dailypilotpayment", name="daily_pilot_payment")
     */
    public function reportDailyPilotPaymentAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = Request::createFromGlobals();
        $pilots = $em->getRepository('\App\Entity\Pilot')->findAll();
        $passengerRepos = $em->getRepository('\App\Entity\Passenger');
        $pilotFlightCommissionRepository = $em->getRepository('\App\Entity\PilotFlightCommission');

        $targetDateStr = $request->query->get('date', '');
        $targetDate = new \DateTime($targetDateStr);

        $endOfDayReport = new EndOfDayReport($targetDate);
        foreach($pilots as $pilot)
        {
            $endOfDayReportPilot = new EndOfDayReportPilot($pilot);
            $endOfDayReport->addPilot($endOfDayReportPilot);

            $passengers = $passengerRepos->getPassengersForPilotOnADate($pilot, $targetDate);

            foreach ($passengers as $passenger)
            {
                $booking = $passenger->getBooking();
                $flightPurchaseItem = null;
                $photoPurchaseItem = null;
                $creditCardFeePurchaseItem = null;
                $pilotFlightCommission = null;
                $cardPayment = false;
                $invoicePayment = false;
                $voucherPayment = false;
                $sumUpPayment = false;

                if($passenger->getPurchase() != null)
                {
                    foreach ($passenger->getPurchase()->getPurchaseItems() as $purchaseItem)
                    {
                        if($purchaseItem->getProduct()->getProductCategory()->getName() == "FLIGHT")
                        {
                            $flightPurchaseItem = $purchaseItem;
                        }
                        if($purchaseItem->getProduct()->getProductCategory()->getName() == "FLIGHT-PHOTO")
                        {
                            $photoPurchaseItem = $purchaseItem;
                        }
                        if($purchaseItem->getProduct()->getProductCategory()->getName() == "CARD-FEE")
                        {
                            $creditCardFeePurchaseItem = $purchaseItem;
                        }
                    }
                    foreach ($passenger->getPurchase()->getPayments() as $payment)
                    {
                        if(!$payment->getRefunded())
                        {
                            if ($payment->getPaymentType()->getName() == 'VISA' ||
                                $payment->getPaymentType()->getName() == 'M/C' ||
                                $payment->getPaymentType()->getName() == 'JCB' ||
                                $payment->getPaymentType()->getName() == 'AMEX' ||
                                $payment->getPaymentType()->getName() == 'MAESTRO')
                            {
                                $cardPayment = true;
                            }
                            if($payment->getPaymentType()->getName() == 'INVOICE')
                            {
                                $invoicePayment = true;
                            }
                            if($payment->getPaymentType()->getName() == 'VOUCHER')
                            {
                                $voucherPayment = true;
                            }
                        }
                    }

                    $flight = $flightPurchaseItem->getProduct();
                    $pilotFlightCommission = $pilotFlightCommissionRepository->getPilotFlightCommission($pilot, $flight);
                }

                if($pilotFlightCommission === null)
                {
                    $commission = 0.0;
                }
                else
                {
                    $commission = $pilotFlightCommission->getFlightCommission();
                }

                $endOfDayReportPilotBooking = new EndOfDayReportPilotBooking($booking, $passenger, $flightPurchaseItem, $photoPurchaseItem, $creditCardFeePurchaseItem, $cardPayment, $invoicePayment, $voucherPayment, $commission);
                $endOfDayReportPilot->addPilotBooking($endOfDayReportPilotBooking);
            }

        }
        return $this->render('report/daily.pilot.payment.html.twig', array(
            'endOfDayReport' => $endOfDayReport));
    }

    /**
     * @Route("/paymenttype", name="payment_type")
     */
    public function paymentTypeReportAction()
    {
        $payments = null;
        $paymentViewList = array();
        $request = Request::createFromGlobals();
        $reposPayment = $this->getDoctrine()->getRepository(Payment::class);
        $reposPaymentType = $this->getDoctrine()->getRepository(PaymentType::class);

        $previousMonth = new \DateTime("now");
        $previousMonth->modify('-1 month');
        $preferredChoices = $reposPaymentType->getSumUpTypePayments();

        $defaultData = [];
        $form = $this->createFormBuilder($defaultData)
            ->add('monthYear', DateType::class, array('data' => $previousMonth,))
            ->add('paymentType', EntityType::class, array('label' => false, 'class' => PaymentType::class,
                'preferred_choices' => $preferredChoices,
                'choice_label' => 'name', 'placeholder' => 'Select Account',
                'expanded' => true, 'multiple' => true,
                'query_builder' => function (EntityRepository $er) { return $er->createQueryBuilder('pt')->orderBy('pt.name', 'ASC'); }
            ))
            ->add('generate', SubmitType::class, array('label' => 'Generate'))
            ->add('cancel', SubmitType::class, array('attr' => array('formnovalidate' => true, )))
            ->getForm();

        $paymentTypes = array();
        $startMonth = clone $previousMonth;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $paymentTypes = $form->get('paymentType')->getData();
            $startMonth = $form->get('monthYear')->getData();
            $startMonth->modify('first day of this month');
            $endMonth = clone $startMonth;
            $endMonth->modify('+ 1 months');
            $payments = $reposPayment->getSumUpPaymentsForDateRange($paymentTypes, $startMonth, $endMonth);
            $paymentViewHelper = new PaymentViewHelper();
            $paymentViewList = $paymentViewHelper->processBookingPayments($payments);
        }

        return $this->render('report/payment.type.html.twig', array(
            'paymentViewList' => $paymentViewList,
            'payments' => $payments,
            'reportPaymentTypes' => $paymentTypes,
            'reportMonth' => $startMonth->format("M"),
            'reportYear' => $startMonth->format("Y"),
            'form' => $form->createView()
        ));
    }

}
