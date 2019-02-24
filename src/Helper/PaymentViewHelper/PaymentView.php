<?php 
namespace App\Helper\PaymentViewHelper;

class PaymentView
{
    private $paymentDateTime;
    private $transactionNo;
    private $sumUpRef;
    private $paymentAmount;
    private $notes;
    private $bookingNo;
    private $flightDate;
    private $meetingTime;
    private $pilots;
    private $passengers;
    private $subPaymentAmounts;
    private $refunded;
    private $refundedDate;
    private $paymentType;

    public function getPaymentDateTime()
    {
        return $this->paymentDateTime;
    }

    public function setPaymentDateTime($paymentDateTime)
    {
        $this->paymentDateTime = $paymentDateTime;
        return $this;
    }

    public function getTransactionNo()
    {
        return $this->transactionNo;
    }

    public function setTransactionNo($transactionNo)
    {
        $this->transactionNo = $transactionNo;
        return $this;
    }

    public function getSumUpRef()
    {
        return $this->sumUpRef;
    }

    public function setSumUpRef($sumUpRef)
    {
        $this->sumUpRef = $sumUpRef;
        return $this;
    }

    public function getPaymentAmount()
    {
        return $this->paymentAmount;
    }

    public function setPaymentAmount($paymentAmount)
    {
        $this->paymentAmount = $paymentAmount;
        return $this;
    }

//     public function setSubPaymentAmounts($subPaymentAmounts)
//     {
//         $this->subPaymentAmounts = $subPaymentAmounts;
//         return $this;
//     }
 
    public function getNotes()
    {
        return $this->notes;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    public function getBookingNo()
    {
        return $this->bookingNo;
    }

    public function setBookingNo($bookingNo)
    {
        $this->bookingNo = $bookingNo;
        return $this;
    }

    public function getFlightDate()
    {
        return $this->flightDate;
    }

    public function setFlightDate($flightDate)
    {
        $this->flightDate = $flightDate;
        return $this;
    }

    public function getMeetingTime()
    {
        return $this->meetingTime;
    }

    public function setMeetingTime($meetingTime)
    {
        $this->meetingTime = $meetingTime;
        return $this;
    }
 
    public function addPilot($pilot)
    {
        $this->pilots[] = $pilot;
    }
    
    public function getPilots()
    {
        return $this->pilots;
    }

    public function addPassenger($passenger)
    {
        $this->passengers[] = $passenger;
    }

    public function getPassengers()
    {
        return $this->passengers;
    }

    public function addSubPaymentAmount($subPaymentAmount)
    {
        $this->subPaymentAmounts[] = $subPaymentAmount;
    }

    public function getSubPaymentAmounts()
    {
        return $this->subPaymentAmounts;
    }

    public function getRefunded()
    {
        return $this->refunded;
    }

    public function setRefunded($refunded)
    {
        $this->refunded = $refunded;
        return $this;
    }

    public function getRefundedDate()
    {
        return $this->refundedDate;
    }

    public function setRefundedDate($refundedDate)
    {
        $this->refundedDate = $refundedDate;
        return $this;
    }

    public function getPaymentType()
    {
        return $this->paymentType;
    }

    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;
        return $this;
    }
 
 
}
