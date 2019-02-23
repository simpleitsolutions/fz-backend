<?php
namespace App\Helper;

class EndOfDayReport
{
    private $reportDate;

    private $pilots;

    public function __construct(\DateTime $reportDate)
    {
        $this->reportDate = $reportDate;
        $this->pilots = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     *
     * @return the DateTime
     */
    public function getReportDate()
    {
        return $this->reportDate;
    }

    /**
     *
     * @param DateTime $reportDate            
     */
    public function setReportDate($reportDate)
    {
        $this->reportDate = $reportDate;
        return $this;
    }
 
    /**
     * Add Pilot End Of Day 
     *
     * @param \App\Helper\EndOfDayReportPilot $pilot
     * @return EndOfDayReportPilot
     */
    public function addPilot(EndOfDayReportPilot $pilot)
    {
        $this->pilots[] = $pilot;
    
        return $this;
    }
    
    /**
     * Remove Pilot End Of Day 
     *
     * @param \App\Helper\EndOfDayReportPilot $pilot
     */
    public function removePilot(EndOfDayReportPilot $pilot)
    {
        $this->pilots->removeElement($pilot);
    }
    
    /**
     * Get Pilot End Of Day 
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPilots()
    {
        return $this->pilots;
    }
    
}
