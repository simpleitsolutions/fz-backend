<?php
namespace App\Helper;

use App\Helper\PaymentViewHelper\BookingPaymentView;
use App\Helper\PaymentViewHelper\PaymentView;

class PaymentViewHelper
{
    private $paymentViewList;

    public function __construct()
    {
        $this->paymentViewList = array();
    }

    public function processBookingPayments($payments)
    {
        foreach ($payments as $payment)
        {
            $transactionNo = $payment->getTransactionNo();
            $passenger = $payment->getPurchases()[0]->getPassenger();
            $pilot = $passenger->getPilot();
            $booking = $passenger->getBooking();
            if(!array_key_exists ( $transactionNo, $this->paymentViewList ))
            {
                $paymentView = new BookingPaymentView();
                $paymentView->setPaymentDateTime($payment->getCreated());
                $paymentView->setTransactionNo($transactionNo);
                $paymentView->setPaymentAmount($payment->getAmount());
                $paymentView->setNotes($payment->getDescription());
                $paymentView->setSumUpRef($payment->getSumupRef());
                $paymentView->addPassenger($passenger);
                if($payment->getSubAmount() <> 0.0)
                {
//                     $paymentView->addSubPaymentAmount($payment->getSubAmount());
                }
                $paymentView->addPilot($pilot);
                $paymentView->setBookingNo($booking->getId()) ;
                $paymentView->setFlightDate($booking->getFlightDate());
                $paymentView->setMeetingTime($booking->getMeetingTime());
                $paymentView->setRefunded($payment->getRefunded());
                $paymentView->setRefundedDate($payment->getUpdated());
                $paymentView->setPaymentType($payment->getPaymentType());
    
                $this->paymentViewList[$transactionNo] = $paymentView;
            }
            else
            {
                $paymentView = $this->paymentViewList[$transactionNo];
    
                $paymentView->addPassenger($passenger);
//                 $paymentView->addSubPaymentAmount($payment->getSubAmount());
                $paymentView->addPilot($pilot);
            }
        }
        return $this->paymentViewList;
    }

    public function processPassengerPayments($payments)
    {
        foreach ($payments as $payment)
        {
            $transactionNo = $payment->getTransactionNo();
            $passenger = $payment->getPurchases()[0]->getPassenger();
            $pilot = $passenger->getPilot();
            $booking = $passenger->getBooking();
            if(!array_key_exists ( $transactionNo, $this->paymentViewList ))
            {
                $paymentView = new BookingPaymentView();
                $paymentView->setPaymentDateTime($payment->getCreated());
                $paymentView->setTransactionNo($transactionNo);
                if($payment->getSubAmount() <> 0.0) //Sub Payment value is set to 0.0 if the Payment applies to only one passenger.
                {
                       $paymentView->addSubPaymentAmount($payment->getSubAmount());
//                         $paymentView->setPaymentAmount($payment->getSubAmount());
                }
//                 else
//                 {
                    $paymentView->setPaymentAmount($payment->getAmount());
//                 }
                $paymentView->setNotes($payment->getDescription());
                $paymentView->setSumUpRef($payment->getSumupRef());
                $paymentView->addPassenger($passenger);
                $paymentView->addPilot($pilot);
                $paymentView->setBookingNo($booking->getId()) ;
                $paymentView->setFlightDate($booking->getFlightDate());
                $paymentView->setMeetingTime($booking->getMeetingTime());
                $paymentView->setRefunded($payment->getRefunded());
                $paymentView->setRefundedDate($payment->getUpdated());
                $paymentView->setPaymentType($payment->getPaymentType());
    
                $this->paymentViewList[$transactionNo] = $paymentView;
            }
            else
            {
                $paymentView = $this->paymentViewList[$transactionNo];
    
                $paymentView->addPassenger($passenger);
                $paymentView->addSubPaymentAmount($payment->getSubAmount());
                $paymentView->addPilot($pilot);
            }
        }
        return $this->paymentViewList;
    }

    public function processVoucherPayments($payments)
    {
        foreach ($payments as $payment)
        {
            $transactionNo = $payment->getTransactionNo();
            if(!array_key_exists ( $transactionNo, $this->paymentViewList ))
            {
                $paymentView = new PaymentView();
                $paymentView->setPaymentDateTime($payment->getCreated());
                $paymentView->setTransactionNo($transactionNo);
                $paymentView->setPaymentAmount($payment->getAmount());
                $paymentView->setNotes($payment->getDescription());
                $paymentView->setSumUpRef($payment->getSumupRef());
                $paymentView->setRefunded($payment->getRefunded());
                $paymentView->setRefundedDate($payment->getUpdated());
                $paymentView->setPaymentType($payment->getPaymentType());

                $this->paymentViewList[$transactionNo] = $paymentView;
            }
        }
        return $this->paymentViewList;
    }
}
