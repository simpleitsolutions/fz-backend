<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WaitingListItemRepository")
 * @ORM\Table(name="waiting_list_item")
 */
class WaitingListItem extends BaseEntity
{
    public function __construct() {
    }

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	  protected $id;

	/** 
	 * @ORM\Column(name="waitingListItemDate", type="date")
	 * @Assert\DateTime(message="Not a valid date.")
	 * @Assert\NotBlank(message="Date is required.")
	 */
	  protected $waitingListItemDate;

	/**
	 * @ORM\Column(type="string", length=80)
	 * @Assert\NotBlank(message="Passenger Name is required.", groups={"quick"})
	 */
	protected $name;
	
	/**
	 * @ORM\Column(type="string", length=300)
	 * @Assert\NotBlank(message="Contact Information is required.")
	 *
	 */
	  protected $contactinfo;

	/**
	 * @ORM\Column(type="integer")
	 */
	  protected $noofpassengers;

	/**
	 * @ORM\Column(type="string", length=300, nullable=true)
	 */
	  protected $notes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     **/
	protected $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="last_updated_by", referencedColumnName="id")
     **/
	protected $lastUpdatedBy;


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
     * @return Passenger
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
     * Set contactinfo
     *
     * @param string $contactinfo
     * @return WaitingListItem
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
     * Set waitingListItemDate
     *
     * @param \DateTime $waitingListItemDate
     * @return WaitingListItem
     */
    public function setWaitingListItemDate($waitingListItemDate)
    {
        $this->waitingListItemDate = $waitingListItemDate;

        return $this;
    }

    /**
     * Get waitingListItemDate
     *
     * @return \Date 
     */
    public function getWaitingListItemDate()
    {
        return $this->waitingListItemDate;
    }

    /**
     * Set noofpassengers
     *
     * @param string $noofpassengers
     * @return WaitingListItem
     */
    public function setNoofpassengers($noofpassengers)
    {
        $this->noofpassengers = $noofpassengers;

        return $this;
    }

    /**
     * Get noofpassengers
     *
     * @return integer 
     */
    public function getNoofpassengers()
    {
        return $this->noofpassengers;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return WaitingListItem
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
     * Set createdBy
     *
     * @param \App\Entity\User $user
     * @return WaitingListItem
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
     * @return WaitingListItem
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
}
