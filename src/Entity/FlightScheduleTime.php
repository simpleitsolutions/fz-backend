<?php 
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FlightScheduleTimeRepository")
 * @ORM\Table(name="flight_time")
 */
class FlightScheduleTime
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    // ...
    /**
     * Many Flight Times have One Flight Schedule.
     * @ORM\ManyToOne(targetEntity="FlightSchedule", inversedBy="flightScheduleTimes")
     * @ORM\JoinColumn(name="flight_schedule_id", referencedColumnName="id")
     */
    private $flightSchedule;

    /**
     * @var \integer $orderIndex
     *
     * @ORM\Column(name="order_index", type="integer")
     */
    private $orderIndex;

    /**
     * @var \DateTime $scheduleStartTime
     *
     * @ORM\Column(name="schedule_start_time", type="time")
     */
    private $scheduleStartTime;
    
     /**
     * @var \DateTime $scheduleEndTime
     *
     * @ORM\Column(name="schedule_end_time", type="time")
     */
    private $scheduleEndTime;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function getFlightSchedule()
    {
        return $this->flightSchedule;
    }

    public function setFlightSchedule($flightSchedule)
    {
        $this->flightSchedule = $flightSchedule;
        return $this;
    }

    /**
     *
     * @return the \integer
     */
    public function getOrderIndex()
    {
        return $this->orderIndex;
    }

    /**
     *
     * @param
     *            $orderIndex
     */
    public function setOrderIndex($orderIndex)
    {
        $this->orderIndex = $orderIndex;
        return $this;
    }
  
    public function getScheduleStartTime()
    {
        return $this->scheduleStartTime;
    }

    public function setScheduleStartTime(\DateTime $scheduleStartTime)
    {
        $this->scheduleStartTime = $scheduleStartTime;
        return $this;
    }

    public function getScheduleEndTime()
    {
        return $this->scheduleEndTime;
    }

    public function setScheduleEndTime(\DateTime $scheduleEndTime)
    {
        $this->scheduleEndTime = $scheduleEndTime;
        return $this;
    }
    
    public function __toString()
    {
        return "Flight ".$this->orderIndex." - ( ".$this->scheduleStartTime->format("H:i")." )";
    }
}
