<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PilotRepository")
 * @ORM\Table(name="pilot")
 */
class Pilot extends BaseEntity
{
    public function __construct() {
        $this->passengers = new ArrayCollection();
        $this->pilotFlightCommissions = new ArrayCollection();
    }

	/**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=30)
     */
    protected $name;

	/**
     * @ORM\OneToMany(targetEntity="Passenger", mappedBy="pilot", cascade={"all"})
     */
    protected $passengers;

	/**
     * @ORM\OneToMany(targetEntity="PilotFlightCommission", mappedBy="pilot")
     */
    private $pilotFlightCommissions;


	protected $commissionRate;

	/**
	 * @ORM\OneToMany(targetEntity="Availability", mappedBy="pilot", cascade={"all"})
	 */
	private $availability;
	
	/**
	 * @ORM\Column(name="flyZermattPilot", type="boolean")
	 */
	private $flyZermattPilot;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="sort_order", type="integer")
     */
    protected $sortOrder;

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
     * Set name
     *
     * @param string $name
     * @return Pilot
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add availability
     *
     * @param \App\Entity\Availability $availability
     * @return Booking
     */
    public function addAvailability(Availability $availability)
    {
        if(!$this->availability->contains($availability))
        {
            $this->availability->add($availability);
        }
        return $this;
    }
    
    /**
     * Remove availability
     *
     * @param \App\Entity\Availability $availability
     */
    public function removeAvailability(Availability $availability)
    {
        $this->availability->removeElement($availability);
    }
    
    /**
     * Get availabilitys
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAvailabilitys()
    {
        return $this->availability;
    }

    public function isFlyZermattPilot()
    {
        return $this->flyZermattPilot;
    }

    /**
     * Set sortOrder
     *
     * @param integer $sortOrder
     * @return Pilot
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get sortOrder
     *
     * @return integer 
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Add passengers
     *
     * @param \App\Entity\Passenger $passengers
     * @return Pilot
     */
    public function addPassenger(Passenger $passengers)
    {
        $this->passengers[] = $passengers;

        return $this;
    }

    /**
     * Remove passengers
     *
     * @param \App\Entity\Passenger $passengers
     */
    public function removePassenger(Passenger $passengers)
    {
        $this->passengers->removeElement($passengers);
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



    /**
     * Add Pilot Flight Commission
     *
     * @param \App\Entity\PilotFlightCommission $pilotFlightCommission
     * @return PilotFlightCommission
     */
    public function addPilotFlightCommission(PilotFlightCommission $pilotFlightCommission)
    {
        $this->pilotFlightCommissions[] = $pilotFlightCommission;
    
        return $this;
    }
    
    /**
     * Remove Pilot Flight Commission
     *
     * @param \App\Entity\PilotFlightCommission $pilotFlightCommission
     */
    public function removePilotFlightCommission(PilotFlightCommission $pilotFlightCommission)
    {
        $this->pilotFlightCommission->removeElement($pilotFlightCommission);
    }
    
    /**
     * Get Pilot Flight Commissions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPilotFlightCommissions()
    {
        return $this->pilotFlightCommissions;
    }

	public function setCommissionRate($commissionRate)
	{
		$this->commissionRate = $commissionRate;
	}

	public function getCommissionRate()
	{
		return $this->commissionRate;
	}

	public function calculateCommissionRate($targetDate)
	{
		$commission;
		
		foreach ($this->getPilotCommissions() as $pilotCommission)
		{
			if($pilotCommission->getActiveEndDate() === NULL)
			{
				$commission = $pilotCommission->getFlightCommission();
			}
			else
			{
				if($targetDate > $pilotCommission->getCreated() && $targetDate < $pilotCommission->getActiveEndDate())
				{
					$commission = $pilotCommission->getFlightCommission();
					return $commission;
				}
			}
		}
		return $commission;
	}

	public function isUnavailable($flightDate, $flightScheduleTime)
	{
        $unavailable = false;
	    foreach($this->availability as $availability)
	    {
	        if($availability->getUnavailableFlightDate()->format("d.m.Y") == $flightDate->format("d.m.Y"))
	        {
	            foreach ($availability->getFlightScheduleTimes() as $unavailableFlightScheduleTime)
	            {
	                if($unavailableFlightScheduleTime->getScheduleStartTime()->format("H:i") == $flightScheduleTime->getFlightScheduleTime()->getScheduleStartTime()->format("H:i"))
	                {
	                    $unavailable = true;
	                }
	            }
            }
        }
	    return $unavailable;
	}

	public function __toString()
    {
        return $this->getName()."";
    }
}

