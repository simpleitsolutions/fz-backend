<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PurchaseItemRepository")
 * @ORM\Table(name="purchase_item")
 * @Gedmo\SoftDeleteable(fieldName="deleted")
 */
class PurchaseItem extends BaseEntity
{
    /**
     * Constructor
     */
    public function __construct(Product $product)
    {
		$this->setProduct($product);
		$this->setDescription($product->getDescription());
		$this->setAmount($product->getPrice());
    }

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=80)
	 */
	protected $description;
	
	/**
	 * @ORM\Column(type="decimal", scale=2)
	 */
	protected $amount;
	
    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     **/
	protected $product;

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
     * Set description
     *
     * @param string $description
     * @return PurchaseItem
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
     * Set amount
     *
     * @param string $amount
     * @return PurchaseItem
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
     * Set product
     *
     * @param \App\Entity\Product $product
     * @return PurchaseItem
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \App\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
}
