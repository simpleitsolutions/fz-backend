<?php 
namespace App\Helper;

use App\Entity\Booking;

class BookingDailySchedule
{
    private $timeSchedules;

    private $bookingsUnallocated;
    
    private $waitingList;

    private $bookingRequests;

    private $pilots;

    public function __construct($pilots)
    {
        $this->pilots = $pilots;
        $this->timeSchedules = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bookingsUnallocated = new \Doctrine\Common\Collections\ArrayCollection();
        $this->waitingList = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bookingRequests = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add TimeSchedule
     *
     * @param App\Helper\BookingTimeSchedule $timeSchedule
     * @return BookingDailySchedule
     */
    public function addTimeSchedule(BookingTimeSchedule $timeSchedule)
    {
        $this->timeSchedules[] = $timeSchedule;
    
        return $this;
    }
    
    /**
     * Remove TimeSchedule
     *
     * @param App\Helper\BookingTimeSchedule $timeSchedule
     */
    public function removeTimeSchedule(BookingTimeSchedule $timeSchedule)
    {
        $this->timeSchedules->removeElement($timeSchedule);
    }
    
    /**
     * Get TimeSchedules
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTimeSchedules()
    {
        return $this->timeSchedules;
    }

    /**
     * Add Bookings Unallocated
     *
     * @param App\Entity\Booking $booking
     * @return BookingDailySchedule
     */
    public function addBookingsUnallocated(Booking $booking)
    {
        $this->bookingsUnallocated[] = $booking;
    
        return $this;
    }
    
    /**
     * Remove Bookings Unallocated
     *
     * @param App\Entity\Booking $booking
     */
    public function removeBookingsUnallocated(Booking $booking)
    {
        $this->bookingsUnallocated->removeElement($booking);
    }
    
    /**
     * Get Bookings Unallocated
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBookingsUnallocated()
    {
        return $this->bookingsUnallocated;
    }

    public function getWaitingList()
    {
        return $this->waitingList;
    }

    public function setWaitingList($waitingList)
    {
        $this->waitingList = $waitingList;
        return $this;
    }

    public function getBookingRequests()
    {
        return $this->bookingRequests;
    }

    public function setBookingRequests($bookingRequests)
    {
        $this->bookingRequests = $bookingRequests;
        return $this;
    }

    public function getPilots()
    {
        return $this->pilots;
    }

    public function setPilots($pilots)
    {
        $this->pilots = $pilots;
        
        return $this;
    }


    public function generateSchedule($indexdate, $bookings, $flightSchedule)
    {
        
        //create Time Schedule items
        if($flightSchedule != null)
        {
            foreach ($flightSchedule->getFlightScheduleTimes() as $flightScheduleTime)
            {
                $bookingTimeSchedule = new BookingTimeSchedule($flightScheduleTime, sizeof($this->pilots));
                $this->timeSchedules->add($bookingTimeSchedule);
            }
        }

        foreach($bookings as $booking)
        {
            $unallocated = true;
            if($flightSchedule != null)
            {

                foreach ($flightSchedule->getFlightScheduleTimes() as $flightScheduleTime)
                {
                    $flightTime = null;
                    if($booking->getMeetingTime() == null)
                    {

                    }
                    else if($booking->getMeetingTime()->format('H:i:s') == '00:00:00' )
                    {
                        $flightTime = $booking->getFlightdate()->format("H:i");
                    }
                    else
                    {
                        $flightTime = $booking->getMeetingTime()->format('H:i');
                    }

                    if($flightScheduleTime == $booking->getFlightScheduleTime()
                        || ($booking->getFlightScheduleTime() == null
                        && ($flightTime >= $flightScheduleTime->getScheduleStartTime()->format("H:i")
                        && $flightTime <= $flightScheduleTime->getScheduleEndTime()->format("H:i"))))
                    {
                        foreach($this->timeSchedules as $timeSchedule)
                        {
                            if($timeSchedule->getFlightScheduleTime() == $flightScheduleTime)
                            {
                                $timeSchedule->addBooking($booking);
                                $unallocated = false;
                            }
                        }
                    }
                }
            }

            if($unallocated)
            {
                $this->bookingsUnallocated->add($booking);
            }
        }

        foreach($this->timeSchedules as $timeSchedule)
        {
            foreach($this->pilots as $pilot)
            {
                if($pilot->isUnavailable($indexdate, $timeSchedule))
                {
                    $timeSchedule->addUnavailablePilot($pilot);
                }
                else
                {
                    $timeSchedule->addAvailablePilot($pilot);
                }
            }
        }

        return $this->bookingsUnallocated;
    }
}

?>
