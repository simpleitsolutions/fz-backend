<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\Table(name="booking")
 */
class Booking extends BaseEntity
{
	const STATUS_NEW = 1;
	const STATUS_CONFIRMED = 2;
	const STATUS_PAYMENT_PART = 3;
    const STATUS_PAYMENT_FULL = 4;
    const STATUS_FULLY_REFUNDED = 5;

    const STATUS_LABELS = ['', 'NEW', 'CONFIRMED', 'PAYMENT PART', 'PAYMENT FULL', 'FULLY REFUNDED'];
    const STATUS_SM_LABELS = ['', 'NEW', 'CONFIRMED', 'PART', 'FULL', 'REFUNDED'];

    public function __construct()
    {
        $this->passengers = new \Doctrine\Common\Collections\ArrayCollection();
		$this->status = self::STATUS_NEW;
    }


	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
     private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
     private $status;

	/**
	 * @ORM\Column(type="datetime")
	 * @Assert\DateTime(message="Flight Date is not a valid date.", groups={"quick"})
	 * @Assert\NotBlank(message="Flight Date is required.", groups={"quick"})
	 * 	 */
     private $flightdate;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * @Assert\NotNull(message="No Flight selected.")
     **/
    private $flight;

    /**
     * @ORM\Column(type="string", length=300)
     * @Assert\NotBlank(message="Contact Information is required.", groups={"quick"})
     */
    private $contactinfo;

    /**
	 * @ORM\Column(type="string", length=1000, nullable=true)
	 */
     private $notes;

	/**
     * @ORM\OneToMany(targetEntity="Passenger", mappedBy="booking", cascade={"all"})
     */
     private $passengers;

     /**
      * @ORM\Column(name="meeting_time", type="time", nullable=true)
      * 	
      */
     private $meetingTime;
     
     /**
     * @ORM\ManyToOne(targetEntity="MeetingLocation", inversedBy="bookings")
     * @ORM\JoinColumn(name="meetinglocation_id", referencedColumnName="id")
	 * @Assert\NotNull(message="No Meeting Location selected.")
     */
     private $meetingLocation;

	/**
	 * @ORM\ManyToOne(targetEntity="FlightScheduleTime")
	 * @ORM\JoinColumn(name="flight_schedule_time_id", referencedColumnName="id")
	 **/
     private $flightScheduleTime;
	
     /**
      * @ORM\ManyToOne(targetEntity="BookingOwner", inversedBy="bookings")
      * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
      */
     private $owner;

     /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     **/
     private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="last_updated_by", referencedColumnName="id")
     **/
     private $lastUpdatedBy;


	/**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Booking
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set flightdate
     *
     * @param \DateTime $flightdate
     * @return Booking
     */
    public function setFlightdate($flightdate)
    {
        $this->flightdate = $flightdate;

        return $this;
    }

    /**
     * Get flightdate
     *
     * @return \DateTime 
     */
    public function getFlightdate()
    {
        return $this->flightdate;
    }

    /**
     * Set flight
     *
     * @param \App\Entity\Product $flight
     * @return Booking
     */
    public function setFlight(Product $flight = null)
    {
        $this->flight = $flight;

        return $this;
    }

    /**
     * Get flight
     *
     * @return \App\Entity\Product
     */
    public function getFlight()
    {
        return $this->flight;
    }

    /**
     * Set contactinfo
     *
     * @param string $contactinfo
     * @return Booking
     */
    public function setContactinfo($contactinfo)
    {
        $this->contactinfo = $contactinfo;

        return $this;
    }

    /**
     * Get contactinfo
     *
     * @return string
     */
    public function getContactinfo()
    {
        return $this->contactinfo;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return Booking
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string 
     */
    public function getNotes()
    {
        return $this->notes;
    }


    /**
     * Add passenger
     *
     * @param \App\Entity\Passenger $passenger
     * @return Booking
     */
    public function addPassenger(Passenger $passenger)
    {
    	if(!$this->passengers->contains($passenger))
		{
			$this->passengers->add($passenger);
		}

    	$passenger->addBooking($this);

        return $this;
    }

    /**
     * Remove passenger
     *
     * @param \App\Entity\Passenger $passenger
     */
    public function removePassenger(Passenger $passenger)
    {
        $this->passengers->removeElement($passenger);
    }

    /**
     * Get passengers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPassengers()
    {
        return $this->passengers;
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
 
    /**
     * Set meetingLocation
     *
     * @param \App\Entity\MeetingLocation $meetingLocation
     * @return Booking
     */
    public function setMeetingLocation(MeetingLocation $meetingLocation = null)
    {
        $this->meetingLocation = $meetingLocation;
    
        return $this;
    }
    
    /**
     * Get meetingLocation
     *
     * @return \App\Entity\MeetingLocation
     */
    public function getMeetingLocation()
    {
        return $this->meetingLocation;
    }
    
    /**
     * Set Flight Schedule Time
     *
     * @param \App\Entity\FlightScheduleTime $flightScheduleTime
     * @return Booking
     */
    public function setFlightScheduleTime(FlightScheduleTime $flightScheduleTime = null)
    {
        $this->flightScheduleTime = $flightScheduleTime;

        return $this;
    }

    /**
     * Get Flight Time
     *
     * @return \App\Entity\FlightScheduleTime 
     */
    public function getFlightScheduleTime()
    {
        return $this->flightScheduleTime;
    }

    /**
     * Set owner
     *
     * @param \App\Entity\BookingOwner $bookingOwner
     * @return Booking
     */
    public function setOwner(BookingOwner $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \App\Entity\BookingOwner
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set createdBy
     *
     * @param \App\Entity\User $user
     * @return Booking
     */
    public function setCreatedBy(User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \App\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set lastUpdatedBy
     *
     * @param \App\Entity\User $user
     * @return Booking
     */
    public function setLastUpdatedBy(User $lastUpdatedBy = null)
    {
        $this->lastUpdatedBy = $lastUpdatedBy;

        return $this;
    }

    /**
     * Get lastUpdatedBy
     *
     * @return \App\Entity\User
     */
    public function getLastUpdatedBy()
    {
        return $this->lastUpdatedBy;
    }


    public function hasPayments()
    {
        foreach ($this->passengers as $passenger)
        {
            if ($passenger->getPurchase() != null)
            {
                return true;
            }
        }
        return false;
    }

    public function hasPassengerPayment()
    {
        foreach($this->passengers as $passenger)
        {
            $passengerPurchase = $passenger->getPurchase();
            // throw new \Exception("Error Processing Request--".get_class($passengerPayments), 1);
            if($passengerPurchase != null)
            {
                foreach ($passengerPurchase->getPayments() as $passengerPayment)
                {
                    if ( sizeof($passengerPayment->getPurchases()) == 1 && !$passengerPayment->getRefunded() )
                    {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function calculateBalance()
    {
        $balanceTotal = 0;
        foreach($this->passengers as $passenger)
        {
            $balanceTotal += $passenger->calculateOwing();
        }
        return $balanceTotal;
    }

    public function calculatePurchaseAmount()
    {
        $purchaseAmount = 0;
        foreach($this->passengers as $passenger)
        {
            $purchaseAmount += $passenger->calculatePurchaseTotal();
        }
        return $purchaseAmount;
    }

    public function isFullyRefunded()
    {
        $fullyRefunded = false;
        if($this->calculateBalance() == $this->calculatePurchaseAmount())
        {
            $fullyRefunded = true;
        }
        return $fullyRefunded;
    }

    public function paidInFull()
    {
        foreach($this->passengers as $passenger)
        {
            // throw new \Exception("Error Processing Request--".$passenger->calculateOwing(), 1);
            if ($passenger->getPurchase() == null || $passenger->calculateOwing() <> 0.0 )
            {
                return false;
            }
        }
        return true;
    }

    public function getSumUpPayments()
    {
        $sumupPayments = new \Doctrine\Common\Collections\ArrayCollection();
        $uniqueTransactions = array();
        foreach($this->getPassengers() as $passenger)
        {
            $passengerSumupPayments = $passenger->getSumupPayments();
            foreach ($passengerSumupPayments as $passengerSumupPayment)
            {
                $transactionNo = $passengerSumupPayment->getTransactionNo();
                if(!in_array($transactionNo, $uniqueTransactions))
                {
                    $sumupPayments->add($passengerSumupPayment);
                    $uniqueTransactions[] = $transactionNo;
                }
            }
        }
        return $sumupPayments;
    }


    public function __toString()
    {
        return $this->id."";
    }

}
