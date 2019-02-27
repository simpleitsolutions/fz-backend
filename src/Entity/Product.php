<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={
 *      "advertised": "exact",
     *      "productCategory": "exact",
 *     }
 * )
@ApiResource(
 *     attributes={"order"={"sortOrder": "ASC"}},
 *     itemOperations={
 *          "get"={
 *              "method"="GET",
 *              "schemes"={"https", "http"}
 *     }},
 *     collectionOperations={
 *          "get"={
 *              "method"="GET",
 *              "schemes"={"https", "http"}
 *     }}
 * ) */
class Product extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="short_description", type="string", length=10)
     */
    private $shortName;

    /**
     * @var decimal
     *
     * @ORM\Column(name="price", type="decimal", scale=2)
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort_order", type="integer")
     */
    private $sortOrder;

    /**
     * @ORM\Column(type="boolean")
     */
    private $advertised;

    /**
     * @ORM\Column(type="boolean")
     */
    private $preferred;

    /**
     * @ORM\ManyToOne(targetEntity="ProductCategory")
     * @ORM\JoinColumn(name="product_category_id", referencedColumnName="id")
     **/
    protected $productCategory;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set shortName
     *
     * @param string $shortName
     * @return Product
     */
    public function setShortName($shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * Get shortName
     *
     * @return string
     */
    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return Product
     */
    public function setPrice($price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * Set sortOrder
     *
     * @param integer $sortOrder
     * @return Product
     */
    public function setSortOrder($sortOrder): self
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get sortOrder
     *
     * @return integer
     */
    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    /**
     * Set advertised
     *
     * @param boolean $advertised
     * @return Product
     */
    public function setAdvertised($advertised): self
    {
        $this->advertised = $advertised;

        return $this;
    }

    /**
     * Get advertised
     *
     * @return boolean
     */
    public function getAdvertised(): ?bool
    {
        return $this->advertised;
    }

    /**
     * Set preferred
     *
     * @param boolean $preferred
     * @return Product
     */
    public function setPreferred($preferred)
    {
        $this->preferred = $preferred;

        return $this;
    }

    /**
     * Get preferred
     *
     * @return boolean
     */
    public function getPreferred()
    {
        return $this->preferred;
    }

    /**
     * Set productCategory
     *
     * @param ProductCategory $productCategory
     * @return Product
     */
    public function setProductCategory(ProductCategory $productCategory = null): self
    {
        $this->productCategory = $productCategory;

        return $this;
    }

    /**
     * Get productCategory
     *
     * @return ProductCategory
     */
    public function getProductCategory():? ProductCategory
    {
        return $this->productCategory;
    }

    public function __toString()
    {
        return $this->getDescription()."";
    }
}
