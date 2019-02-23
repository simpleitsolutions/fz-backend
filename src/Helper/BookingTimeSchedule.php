<?php
namespace App\Helper;

use App\Entity\Booking;
use App\Entity\FlightScheduleTime;
use App\Entity\Pilot;

class BookingTimeSchedule
{
    private $unavailablePilots;

    private $availablePilots;

    private $bookings;

    private $flightScheduleTime;
    
    private $noOfPilots;

    public function __construct(FlightScheduleTime $flightScheduleTime, $noOfPilots)
    {
        $this->flightScheduleTime = $flightScheduleTime;
        $this->noOfPilots = $noOfPilots;
        $this->unavailablePilots = new \Doctrine\Common\Collections\ArrayCollection();
        $this->availablePilots = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bookings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add Unavailable Pilot
     *
     * @param App\Entity\Pilot $pilot
     * @return BookingTimeSchedule
     */
    public function addUnavailablePilot(Pilot $unavailablePilots)
    {
        $this->unavailablePilots[] = $unavailablePilots;
    
        return $this;
    }
    
    /**
     * Remove Unavailable Pilot
     *
     * @param App\Entity\Pilot $unavailablePilots
     */
    public function removeUnavailablePilot(Pilot $unavailablePilots)
    {
        $this->unavailablePilots->removeElement($unavailablePilots);
    }
    
    /**
     * Get Unavailable Pilots
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUnavailablePilots()
    {
        return $this->unavailablePilots;
    }
    
    /**
     * Add Available Pilot
     *
     * @param App\Entity\Pilot $pilot
     * @return BookingTimeSchedule
     */
    public function addAvailablePilot(Pilot $availablePilots)
    {
        $this->availablePilots[] = $availablePilots;
    
        return $this;
    }
    
    /**
     * Remove Available Pilot
     *
     * @param App\Entity\Pilot $availablePilots
     */
    public function removeAvailablePilot(Pilot $availablePilots)
    {
        $this->availablePilots->removeElement($availablePilots);
    }
    
    /**
     * Get Available Pilots
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAvailablePilots()
    {
        return $this->availablePilots;
    }
    
    /**
     * Add Booking
     *
     * @param App\Entity\Booking $booking
     * @return BookingTimeSchedule
     */
    public function addBooking(Booking $booking)
    {
        $this->bookings[] = $booking;
    
        return $this;
    }
    
    /**
     * Remove Booking
     *
     * @param App\Entity\Booking $booking
     */
    public function removeBooking(Booking $booking)
    {
        $this->bookings->removeElement($booking);
    }
    
    /**
     * Get Bookings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBookings()
    {
        return $this->bookings;
    }
    
    
    public function getFlightScheduleTime()
    {
        return $this->flightScheduleTime;
    }

    public function setFlightScheduleTime(FlightScheduleTime $flightScheduleTime)
    {
        $this->flightScheduleTime = $flightScheduleTime;
        return $this;
    }

    public function getNoOfPilotsAvailable()
    {
        $totalPilots = $this->noOfPilots;
        $totalPassengersBooked = 0;
        foreach ($this->bookings as $booking)
        {
            $totalPassengersBooked = $totalPassengersBooked + sizeof($booking->getPassengers());
        }
        $unavailablePilots = sizeof($this->unavailablePilots);
        return $totalPilots - $totalPassengersBooked - $unavailablePilots;
    }
}
