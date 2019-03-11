<?php 
namespace App\Helper\PaymentViewHelper;

class BookingPaymentView extends PaymentView
{
    private $bookingNo;
    private $flightDate;
    private $meetingTime;
    private $pilots;
    private $passengers;
    private $subPaymentAmounts;

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
}
