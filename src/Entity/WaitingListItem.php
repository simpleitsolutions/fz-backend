<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WaitingListItemRepository")
 * @ORM\Table(name="waiting_list_item")
 * @Gedmo\SoftDeleteable(fieldName="deleted")
 */
class WaitingListItem
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
	
	    /**
     * @var \DateTime $deleted
     *
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    private $deleted;


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

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return WaitingListItem
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
     * @return WaitingListItem
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

    /**
     * Set deleted
     *
     * @param \DateTime $deleted
     * @return WaitingListItem
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