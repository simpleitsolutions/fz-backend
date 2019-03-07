<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PilotFlightCommissionRepository")
 * @ORM\Table(name="pilot_flight_commission")
 * @Gedmo\SoftDeleteable(fieldName="deleted")
 */


class PilotFlightCommission
{
    public function __construct() {
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
     * @var integer
     *
     * @ORM\Column(name="flight_commission", type="decimal", scale=2)
     */
    protected $flightCommission;
	  
    /**
     * @ORM\ManyToOne(targetEntity="Pilot", inversedBy="pilotFlightCommissions")
     * @ORM\JoinColumn(name="pilot_id", referencedColumnName="id")
     */
    protected $pilot;
    
    /**
     * @ ORM\ManyToOne(targetEntity="Product", inversedBy="pilotFlightCommissions")
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_flight_id", referencedColumnName="id")
     */
    protected $flight;
    
    /**
     * @var \Datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

	/**
     * @var \DateTime $deleted
     *
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    private $deleted;

	/**
     * @var \DateTime $activeEndDate
     *
     * @ORM\Column(name="active_end_date", type="datetime", nullable=true)
     */
    private $activeEndDate;

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
     * Set flightCommission
     *
     * @param string $flightCommission
     * @return Pilot
     */
    public function setFlightCommission($flightCommission)
    {
        $this->flightCommission = $flightCommission;

        return $this;
    }

    /**
     * Get flightCommission
     *
     * @return decimal 
     */
    public function getFlightCommission()
    {
        return $this->flightCommission;
    }

    /**
     * Set pilot
     *
     * @param \App\Entity\Pilot $pilot
     * @return Passenger
     */
    public function setPilot(Pilot $pilot)
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
     * @return mixed
     */
    public function getFlight()
    {
        return $this->flight;
    }

    /**
     * @param mixed $flight
     */
    public function setFlight($flight): void
    {
        $this->flight = $flight;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return PilotFlightCommission
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
     * Set activeEndDate
     *
     * @param \DateTime $activeEndDate
     * @return PilotFlightCommission
     */
    public function setActiveEndDate($activeEndDate)
    {
        $this->activeEndDate = $activeEndDate;

        return $this;
    }

    /**
     * Get activeEndDate
     *
     * @return \DateTime 
     */
    public function getActiveEndDate()
    {
        return $this->activeEndDate;
    }

    /**
     * Set deleted
     *
     * @param \DateTime $deleted
     * @return Pilot
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return \DateTime 
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    
}
