<?php 
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FlightScheduleRepository")
 * @ORM\Table(name="flight_schedule")
 */
class FlightSchedule
{
	
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime $affectiveStartDate
     *
     * @ORM\Column(name="affective_start_date", type="date")
     */
    private $affectiveStartDate;
    
        /**
	 * @var \DateTime $affectiveEndDate
	 *
	 * @ORM\Column(name="affective_end_date", type="date", nullable=true)
	 */
	private $affectiveEndDate;

	/**
	 * One Flight Schedule has Many Flight Times.
	 * @ORM\OneToMany(targetEntity="FlightScheduleTime", mappedBy="flightSchedule")
	 */
	private $flightScheduleTimes;
	
	public function __construct()
	{
	    $this->flightScheduleTimes = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
	    return $this->id;
	}
	
    public function getAffectiveStartDate()
    {
        return $this->affectiveStartDate;
    }

    public function setAffectiveStartDate(\DateTime $affectiveStartDate)
    {
        $this->affectiveStartDate = $affectiveStartDate;
        return $this;
    }

    public function getAffectiveEndDate()
    {
        return $this->affectiveEndDate;
    }
    
    public function setAffectiveEndDate(\DateTime $affectiveEndDate)
    {
        $this->affectiveEndDate = $affectiveEndDate;
        return $this;
    }
    
    /**
     * Add flightScheduleTime
     *
     * @param \App\Entity\FlightScheduleTime $flightScheduleTime
     * @return FlightSchedule
     */
    public function addFlightScheduleTime(FlightScheduleTime $flightScheduleTime)
    {
        if(!$this->flightScheduleTimes->contains($flightScheduleTime))
        {
            $this->flightScheduleTimes->add($flightScheduleTime);
        }
        return $this;
    }
    
    /**
     * Remove flightScheduleTime
     *
     * @param \App\Entity\FlightScheduleTime $flightScheduleTime
     */
    public function removeFlightScheduleTime(FlightScheduleTime $flightScheduleTime)
    {
        $this->flightScheduleTimes->removeElement($flightScheduleTime);
    }
    
    /**
     * Get flightScheduleTimes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFlightScheduleTimes()
    {
        return $this->flightScheduleTimes;
    }    

    public function __toString()
    {
        return "Flight Schedule";
    }
}

