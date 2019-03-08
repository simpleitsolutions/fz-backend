<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PassengerRepository")
 * @ORM\Table(name="passenger")
 */


class Passenger extends BaseEntity
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
	 * @ORM\Column(type="string", length=80)
	 * @Assert\NotBlank(message="Passenger Name is required.", groups={"quick"})
	 */
	protected $name;
	

	/**
     * @ORM\ManyToOne(targetEntity="Booking", inversedBy="passengers")
     * @ORM\JoinColumn(nullable=false, name="booking_id", referencedColumnName="id")
     */
    protected $booking;

	/**
     * @ORM\ManyToOne(targetEntity="Pilot", inversedBy="passengers")
     * @ORM\JoinColumn(name="pilot_id", referencedColumnName="id")
     */
    protected $pilot;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     **/
	protected $flight;

    /**
     * @ORM\OneToOne(targetEntity="Purchase", inversedBy="passenger", cascade={"all"})
     * @ORM\JoinColumn(name="purchase_id", referencedColumnName="id")
     **/
	 protected $purchase;

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
     * Add booking
     *
     * @param \App\Entity\Booking $booking
     * @return Passenger
     */
    public function addBooking(Booking $booking)
    {
		$this->booking = $booking;

        return $this;
    }

    /**
     * Set booking
     *
     * @param \App\Entity\Booking $booking
     * @return Passenger
     */
    public function setBooking(Booking $booking)
    {
        $this->booking = $booking;

        return $this;
    }

    /**
     * Get booking
     *
     * @return \App\Entity\Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }

    /**
     * Set pilot
     *
     * @param \App\Entity\Pilot $pilot
     * @return Passenger
     */
    public function setPilot(Pilot $pilot = null)
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
     * Set purchase
     *
     * @param \App\Entity\Purchase $purchase
     * @return Passenger
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

    /**
     * Set flight
     *
     * @param \App\Entity\Product $flight
     * @return Passenger
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

	public function calculatePilotPayment()
	{
		$pilotPaymentTotal = 0.0;
		$paymentAmount = 0.0;
		foreach ($this->purchase->getPurchaseItems() as $purchaseItem)
		{
			if($purchaseItem->getProduct()->getProductCategory()->getName() == 'FLIGHT')
			{
				if($this->purchase->paidCash())
				{
					// $paymentAmount = $purchaseItem->getAmount() - $this->pilot->calculateCommissionRate();
				}
			}
			else
			{
				$paymentAmount = $purchaseItem->getAmount();
			}
			$pilotPaymentTotal += $paymentAmount;
		}

		return $pilotPaymentTotal;		
	}

	public function getFlightCost()
	{
		$pilotPaymentTotal = 0.0;
		$paymentAmount = 0.0;
		foreach ($this->purchase->getPurchaseItems() as $purchaseItem)
		{
			if($purchaseItem->getProduct()->getProductCategory()->getName() == 'FLIGHT')
			{
				$pilotPaymentTotal += $purchaseItem->getAmount();
			}
		}
		return $pilotPaymentTotal;		
	}

	public function getPhotoCost()
	{
		$pilotPaymentTotal = 0.0;
		$paymentAmount = 0.0;
		foreach ($this->purchase->getPurchaseItems() as $purchaseItem)
		{
			if($purchaseItem->getProduct()->getProductCategory()->getName() == 'FLIGHT-PHOTO')
			{
				$pilotPaymentTotal += $purchaseItem->getAmount();
			}
		}
		return $pilotPaymentTotal;		
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

	public function calculatePaid()
	{
		if($this->purchase != null)
		{
			return $this->purchase->calculateOwing();
		}
	}

	public function calculatePurchaseTotal()
	{
		if($this->purchase != null)
		{
			return $this->purchase->calculatePurchaseTotal();
		}
		else
		{
			return $this->flight->getPrice();
		}
	}

	public function getPayments()
	{
		if($this->purchase != null)
		{
			// return $this->purchase->getPayments();
			return $this->purchase();
		}
	}

    public function getSumupPayments()
    {
        $sumupPayments = new \Doctrine\Common\Collections\ArrayCollection();
        if($this->purchase != null)
        {
            foreach($this->purchase->getPayments() as $purchasePayment)
            {
                if($purchasePayment->getPaymentType()->isSumupPayment() &&
                    $purchasePayment->getRefunded() == false &&
                    $purchasePayment->getAmount() <> 0.0)
                {
                    $sumupPayments->add($purchasePayment);
                }
            }
        }
        return $sumupPayments;
    }

	public function paidInFull()
	{
		if($this->purchase != null)
		{
			if($this->purchase->calculateOwing() == 0.0)
			{
				return true;
			}
		}
		return false;
	}

	public function hasMadePayment()
	{
		if($this->purchase != null)
		{
			return true;
		}
		return false;
	}

	public function hasBookingPayment()
	{
		if(!$this->purchase == null)
		{
			foreach ($this->purchase->getPayments() as $payment)
			{
				if(!$payment->getRefunded() && sizeof($payment->getPurchases()) > 1)
				{
					return true;
				}
			}
		}
		return false;
	}

	public function __toString()
    {
        return $this->name."";
    }
}
