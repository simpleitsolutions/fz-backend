<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PurchaseRepository")
 * @ORM\Table(name="purchase")
 */
class Purchase extends BaseEntity
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->purchaseItems = new \Doctrine\Common\Collections\ArrayCollection();
        $this->payments = new \Doctrine\Common\Collections\ArrayCollection();
    }

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="PurchaseItem", cascade={"all"})
     * @ORM\JoinTable(name="purchase_purchase_items",
     *      joinColumns={@ORM\JoinColumn(name="purchase_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="purchase_item_id", referencedColumnName="id", unique=true)}
     *      )
     **/
    protected $purchaseItems;

    /**
     * @ORM\ManyToMany(targetEntity="Payment", inversedBy="purchases", cascade={"all"})
     * @ORM\JoinTable(name="purchases_payments",
     *      joinColumns={@ORM\JoinColumn(name="purchase_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="payment_id", referencedColumnName="id")}
     *      )
     **/
    protected $payments;

    /**
     * @ORM\OneToOne(targetEntity="Passenger", mappedBy="purchase")
     **/
	protected $passenger;

    /**
     * @Assert\NotNull()
     */
	protected $paymentAmount;

    /**
     * @Assert\Valid
     * @Assert\NotNull()
     */
	protected $paymentType;


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
     * Add purchaseItems
     *
     * @param \App\Entity\PurchaseItem $purchaseItems
     * @return Purchase
     */
    public function addPurchaseItem(PurchaseItem $purchaseItems)
    {
        $this->purchaseItems[] = $purchaseItems;

        return $this;
    }

    /**
     * Remove purchaseItems
     *
     * @param \App\Entity\PurchaseItem $purchaseItems
     */
    public function removePurchaseItem(PurchaseItem $purchaseItems)
    {
        $this->purchaseItems->removeElement($purchaseItems);
    }

    /**
     * Get purchaseItems
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPurchaseItems()
    {
        return $this->purchaseItems;
    }

    /**
     * Add payments
     *
     * @param \App\Entity\Payment $payments
     * @return Purchase
     */
    public function addPayment(Payment $payments)
    {
        $this->payments[] = $payments;

        return $this;
    }

    /**
     * Remove payments
     *
     * @param \App\Entity\Payment $payments
     */
    public function removePayment(Payment $payments)
    {
        $this->payments->removeElement($payments);
    }

    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * Set passenger
     *
     * @param \App\Entity\Passenger $passenger
     * @return Purchase
     */
    public function setPassenger(Passenger $passenger = null)
    {
        $this->passenger = $passenger;

        return $this;
    }

    /**
     * Get passenger
     *
     * @return \App\Entity\Passenger 
     */
    public function getPassenger()
    {
        return $this->passenger;
    }

    /**
     * Set paymentType
     *
     * @param \App\Entity\PaymentType $paymentType
     * @return Purchase
     */
    public function setPaymentType(PaymentType $paymentType = null)
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * Get paymentType
     *
     * @return \App\Entity\PaymentType 
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }


    /**
     * Set paymentAmount
     *
     * @param decimal $paymentAmount
     * @return Purchase
     */
    public function setPaymentAmount($paymentAmount = 0.0)
    {
        $this->paymentAmount = $paymentAmount;

        return $this;
    }

    /**
     * Get paymentAmount
     *
     * @return decimal 
     */
    public function getPaymentAmount()
    {
        return $this->paymentAmount;
    }



	public function calculateOwing()
	{
		return $this->calculatePurchaseTotal() - $this->calculatePaymentTotal();
	}

	public function calculatePurchaseTotal()
	{
		$total = 0.0;
		foreach ($this->purchaseItems as $purchaseItem)
		{
			$total += $purchaseItem->getAmount();
		}
		return $total;
	}

	public function calculatePaymentTotal()
	{
		$paymentTotal = 0.0;
		foreach ($this->payments as $payment)
		{
			if(!$payment->getRefunded())
			{
				$paymentTotal += $payment->calculateAmount();
			}
		}
		return $paymentTotal;
	}

	public function hasNoPriorCardPayment()
	{
		foreach ($this->payments as $payment)
		{
			if((($payment->getPaymentType()->getName() == 'VISA' || 
				 $payment->getPaymentType()->getName() == 'M/C' || 
				 $payment->getPaymentType()->getName() == 'MAESTRO' || 
				 $payment->getPaymentType()->getName() == 'AMEX' || 
				 $payment->getPaymentType()->getName() == 'JCB')))
				 {
				 	return true;
				 }
		}
		return false;
	}

	// public function calculateBalance()
	// {
		// return $this->calculatePurchaseTotal() - $this->calculatePaymentTotal();
	// }

	public function calculateFee()
	{
		$paymentFees = array();

		foreach($this->payments as $payment)
		{
			if(!$payment->getRefunded() && $payment->getAmount() >= 170.00)
			{
				$paymentFees[] = $payment->getPaymentType()->getFee();
			}
		}
		return max($paymentFees);
	}
	
	// public function paidCash()
	// {
		// foreach($this->payments as $payment)
		// {
			// if($payment->getPaymentType()->getName() == 'CASH' && !$payment->getRefunded())
			// {
				// return true;
			// }
		// }
		// return false;
	// }

	public function paidCard()
	{
		foreach($this->payments as $payment)
		{
			if(($payment->getPaymentType()->getName() == 'AMEX' || $payment->getPaymentType()->getName() == 'MAESTRO' || $payment->getPaymentType()->getName() == 'VISA' || $payment->getPaymentType()->getName() == 'M/C' || $payment->getPaymentType()->getName() == 'JCB')  && !$payment->getRefunded())
			{
				return true;
			}
		}
		return false;
	}

	public function paidInvoice()
	{
		foreach($this->payments as $payment)
		{
			if($payment->getPaymentType()->getName() == 'INVOICE' && !$payment->getRefunded())
			{
				return true;
			}
		}
		return false;
	}

    public function __toString()
    {
        return $this->id."";
    }

}
