<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoucherRepository")
 * @ORM\Table(name="voucher")
 */
class Voucher extends BaseEntity
{
    const ENGLISH = 0;
    const GERMAN = 1;

    const STATUS_NEW = 1;
    const STATUS_PAYMENT_PART = 2;
    const STATUS_PAYMENT_FULL = 3;
    const STATUS_FULLY_REFUNDED = 4;

    const STATUS_LABELS = ['', 'NEW', 'PAYMENT PART', 'PAYMENT FULL', 'FULLY REFUNDED'];
    const STATUS_SM_LABELS = ['', 'NEW', 'PART', 'FULL', 'REFUNDED'];

    public function __construct() {
    }

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

	/**
	 * @ORM\Column(type="string", length=80)
	 * @Assert\NotBlank(message="Name is required.")
	 */
	protected $name;

	/**
	 * @ORM\Column(name="withPhotos", type="boolean")
	 * @Assert\NotBlank(message="With or without photos.")
	 */
	protected $withPhotos;

	/**
	 * @ORM\Column(type="string", length=20)
	 * @Assert\NotBlank(message="Choose language")
	 */
	protected $language;
	
    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
	 * @Assert\NotNull(message="No Flight selected.")
     **/
	protected $flight;

	/** 
	 * @ORM\Column(type="datetime", nullable=true)
	 * @Assert\DateTime(message="Flight Date is not a valid date.")
	 */
	 protected $flightdate;

	/**
	 * @ORM\Column(type="string", length=300, nullable=true)
	 */
	protected $message;

    /**
     * @ORM\OneToOne(targetEntity="Purchase", cascade={"all"})
     * @ORM\JoinColumn(name="purchase_id", referencedColumnName="id")
     **/
	 protected $purchase;

    /**
	 * @ORM\Column(type="string", length=300, nullable=true)
	 */
	 protected $notes;

	// protected $payment;
	
	// protected soldby;

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
     * @return Voucher
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
     * Set name
     *
     * @param string $name
     * @return Voucher
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
     * Set withPhotos
     *
     * @param string $withPhotos
     * @return Voucher
     */
    public function setWithPhotos($withPhotos)
    {
        $this->withPhotos = $withPhotos;

        return $this;
    }

    /**
     * Get withPhotos
     *
     * @return string 
     */
    public function getWithPhotos()
    {
        return $this->withPhotos;
    }

    /**
     * Set language
     *
     * @param string $language
     * @return Voucher
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage()
    {
        return $this->language;
    }


    /**
     * Set message
     *
     * @param string $message
     * @return Voucher
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set flightdate
     *
     * @param \DateTime $flightdate
     * @return Voucher
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
     * @return Voucher
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
     * Set notes
     *
     * @param string $notes
     * @return Voucher
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
     * Set purchase
     *
     * @param \App\Entity\Purchase $purchase
     * @return Voucher
     */
    public function setPurchase(Purchase $purchase = null)
    {
        $this->purchase = $purchase;

        return $this;
    }

    /**
     * Get purchase
     *
     * @return \App\Entity\Purchase 
     */
    public function getPurchase()
    {
        return $this->purchase;
    }

    public function calculateOwing()
    {
        if($this->purchase != null)
        {
            return $this->purchase->calculateOwing();
        }
        else
        {
            return $this->flight->getPrice();
        }
    }

    public function paidInFull()
    {
        $paidInFull = false;
        if($this->calculateOwing() == 0)
        {
            $paidInFull = true;
        }
        return $paidInFull;
    }

    public function __toString()
    {
        return $this->name."";
    }
}
