<?php
namespace App\Helper;

use App\Entity\Pilot;

class EndOfDayReportPilot
{
    private $pilot;

    private $pilotBookings;

    public function __construct(Pilot $pilot)
    {
        $this->pilot = $pilot;
        $this->pilotBookings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     *
     * @return the Pilot Name
     */
    public function getPilotName()
    {
        return $this->pilot->getName();
    }

    /**
     * Add pilotBooking
     *
     * @param \App\Helper\EndOfDayReportPilotBooking $pilotBooking
     * @return Purchase
     */
    public function addPilotBooking(EndOfDayReportPilotBooking $pilotBooking)
    {
        $this->pilotBookings[] = $pilotBooking;
    
        return $this;
    }
    
    /**
     * Remove pilotBooking
     *
     * @param \App\Helper\EndOfDayReportPilotBooking $pilotBooking
     */
    public function removePilotBooking(EndOfDayReportPilotBooking $pilotBooking)
    {
        $this->pilotBookings->removeElement($pilotBooking);
    }
    
    /**
     * Get pilotBookings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPilotBookings()
    {
        return $this->pilotBookings;
    }

    public function getTotalPilotPayment()
    {
        $totalPayment = 0.0;
        foreach ($this->pilotBookings as $pilotBooking)
        {
            $totalPayment = $totalPayment + $pilotBooking->getTotalPilotBookingPayment();
        }
        return $totalPayment;
    }
}
