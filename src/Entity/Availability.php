<?php 
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AvailabilityRepository")
 * @ORM\Table(name="availability")
 */
class Availability
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime $unavailableFlightDate
     *
     * @ORM\Column(name="unavailable_flight_date", type="date")
     */
    private $unavailableFlightDate;

    /**
     * @ORM\ManyToOne(targetEntity="Pilot", inversedBy="availability")
     * @ORM\JoinColumn(nullable=false, name="pilot_id", referencedColumnName="id")
     */
    private $pilot;

    /**
     * Many Availability have Many FlightScheduleTimes.
     * @ORM\ManyToMany(targetEntity="FlightScheduleTime")
     * @ORM\JoinTable(name="availability_flight_time",
     *      joinColumns={@ORM\JoinColumn(name="availability_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="flight_time_id", referencedColumnName="id", unique=false)}
     *      )
     */
    private $flightScheduleTimes;

    /**
     * @var \Datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;
    
    /**
     * @var \Datetime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;
    
    
    public function __construct()
    {
        $this->flightScheduleTimes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function getId()
    {
        return $this->id;
    }
    
    public function getUnavailableFlightDate()
    {
        return $this->unavailableFlightDate;
    }

    public function setUnavailableFlightDate($unavailableFlightDate)
    {
        $this->unavailableFlightDate = $unavailableFlightDate;
        return $this;
    }

    /**
     * Set pilot
     *
     * @param \App\Entity\Pilot $pilot
     * @return Availability
     */
    public function setPilot(\App\Entity\Pilot $pilot)
    {
        $this->pilot = $pilot;
    
        return $this;
    }
    
    /**
     * Get pilot
     *
     * @return \App\Entity\Pilot
     */
    public function getPilot()
    {
        return $this->pilot;
    }
    
    /**
     * Add flightScheduleTime
     *
     * @param \App\Entity\FlightScheduleTime $flightScheduleTime
     * @return Availability
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

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Booking
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }
    
    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
    
    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Booking
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    
        return $this;
    }
    
    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
    
    public function __toString()
    {
        return $this->unavailableFlightDate===null?"Availability":$this->unavailableFlightDate->format("d-m-Y")."";
    }

}
