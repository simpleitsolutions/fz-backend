<?php
namespace App\Controller;


use App\Helper\EndOfDayReport;
use App\Helper\EndOfDayReportPilot;
use App\Helper\EndOfDayReportPilotBooking;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
                    $cardPayment = false;
                    $invoicePayment = false;
                    $voucherPayment = false;
                    $sumUpPayment = false;
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

}
