<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentRepository")
 * @ORM\Table(name="payment")
 */
class Payment extends BaseEntity
{
    public function __construct() {
    	$this->refunded = false;
        $this->purchases = new \Doctrine\Common\Collections\ArrayCollection();
    }

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var decimal
	 *
	 * @ORM\Column(name="amount", type="decimal", scale=2)
	 */
	protected $amount;
	
	    /**
     * @var decimal
     *
     * @ORM\Column(name="sub_amount", type="decimal", scale=2, nullable=true)
     */
    protected $subAmount;

/**
     * @ORM\ManyToOne(targetEntity="PaymentType", inversedBy="payments")
     * @ORM\JoinColumn(name="payment_type_id", referencedColumnName="id")
	 * @Assert\NotNull(message="No Payment Type selected.")
     */
	protected $paymentType;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="sumupRef", type="string", length=5, nullable=true)
	 */
	protected $sumupRef;
	
	    /**
     * @var string
     *
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    protected $description;

/**
     * @ORM\Column(type="boolean")
     */
    protected $refunded;

    /**
     * @var int
     *
     * @ORM\Column(name="transactionNo", type="string", length=15, nullable=true)
     */
    protected $transactionNo;

    /**
	 * @ORM\ManyToMany(targetEntity="Purchase", mappedBy="payments", cascade={"all"})
     */
    protected $purchases;

    public function calculateAmount()
    {
        if($this->subAmount <> 0.0)
        {
            return $this->subAmount;
        }
//     	if(count($this->purchases) > 0)
// 		{
// 			 return $this->amount/count($this->purchases);
// 		}
    	return $this->amount;
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

    /**
     * Set amount
     *
     * @param string $amount
     * @return Payment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }
    
    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set subAmount
     *
     * @param string $subAmount
     * @return Payment
     */
    public function setSubAmount($subAmount)
    {
        $this->subAmount = $subAmount;

        return $this;
    }

    /**
     * Get subAmount
     *
     * @return string 
     */
    public function getSubAmount()
    {
        return $this->subAmount;
    }

/**
     * Set paymentType
     *
     * @param \App\Entity\PaymentType $paymentType
     * @return Payment
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

    public function getSumupRef()
    {
        return $this->sumupRef;
    }

    public function setSumupRef($sumupRef)
    {
        $this->sumupRef = $sumupRef;
        return $this;
    }
     
    /**
     * Set description
     *
     * @param string $description
     * @return Payment
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set refunded
     *
     * @param boolean $refunded
     * @return Payment
     */
    public function setRefunded($refunded)
    {
        $this->refunded = $refunded;

        return $this;
    }

    /**
     * Get refunded
     *
     * @return boolean 
     */
    public function getRefunded()
    {
        return $this->refunded;
    }

    public function getTransactionNo()
    {
        return $this->transactionNo;
    }

    public function setTransactionNo($transactionNo)
    {
        $this->transactionNo = $transactionNo;
        return $this;
    }
 
    /**
     * Add purchases
     *
     * @param \App\Entity\Purchase $purchases
     * @return Payment
     */
    public function addPurchase(Purchase $purchases)
    {
        $this->purchases[] = $purchases;

        return $this;
    }

    /**
     * Remove purchases
     *
     * @param \App\Entity\Purchase $purchases
     */
    public function removePurchase(Purchase $purchases)
    {
        $this->purchases->removeElement($purchases);
    }

    /**
     * Get purchases
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPurchases()
    {
        return $this->purchases;
    }

    public function __toString()
    {
        return $this->transactionNo."";
    }
}
