<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentTypeRepository")
 * @ORM\Table(name="payment_type")
 * @Gedmo\SoftDeleteable(fieldName="deleted")
 */
class PaymentType
{
    public function __construct()
    {
        $this->payments = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="paymentType")
     */
    protected $payments;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=30)
     */
    protected $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="shortname", type="string", length=4)
     */
    protected $shortName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="printname", type="string", length=10)
     */
    protected $printName;

    /**
     * @var string
     *
     * @ORM\Column(name="icon_path", type="string", length=60)
     */
    protected $iconPath;

    /**
     * @var decimal
     *
     * @ORM\Column(name="fee", type="decimal", scale=2)
     */
    protected $fee;

    /**
     * @ORM\Column(name="sumUpPayment", type="boolean")
     */
    private $sumUpPayment;

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
     * Set created
     *
     * @param \DateTime $created
     * @return PaymentType
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
     * @return PaymentType
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
     * @return PaymentType
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

    /**
     * Add payments
     *
     * @param \App\Entity\Payment $payments
     * @return PaymentType
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
     * Set name
     *
     * @param string $name
     * @return PaymentType
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

    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    
        return $this;
    }

    public function getShortName()
    {
        return $this->shortName;
    }

    public function getPrintName()
    {
        return $this->printName;
    }

    public function setPrintName($printName)
    {
        $this->printName = $printName;
        return $this;
    }
 
    /**
     * Set iconPath
     *
     * @param string $iconPath
     * @return PaymentType
     */
    public function setIconPath($iconPath)
    {
        $this->iconPath = $iconPath;

        return $this;
    }

    /**
     * Get iconPath
     *
     * @return string 
     */
    public function getIconPath()
    {
        return $this->iconPath;
    }

    /**
     * Set fee
     *
     * @param string $fee
     * @return PaymentType
     */
    public function setFee($fee)
    {
        $this->fee = $fee;

        return $this;
    }

    /**
     * Get fee
     *
     * @return string 
     */
    public function getFee()
    {
        return $this->fee;
    }

    public function isCardPayment()
    {
        if($this->getName() === "VISA" ||
            $this->getName() === "M/C" ||
            $this->getName() === "JCB" ||
            $this->getName() === "AMEX" ||
            $this->getName() === "Maestro")
        {
            return true;
        }
        return false;
    }

    public function isVoucherPayment()
    {
        if($this->getName() === "VOUCHER")
        {
            return true;
        }
        return false;
    }

    public function isSumUpPayment()
    {
        return $this->sumUpPayment;
    }

}
