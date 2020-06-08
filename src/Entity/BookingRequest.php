<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRequestRepository")
 * @Gedmo\SoftDeleteable(fieldName="deleted")
 * @//UniqueEntity("email")
 * @ApiResource(
 *     itemOperations={"get"},
 *     collectionOperations={"post"}
 * )
 */
class BookingRequest extends BaseEntityLegacy
{
    const STATUS_UNCONFIRMED = 0;
    const STATUS_CONFIRMED = 1;

    public function __construct()
    {
        $this->confirmed = 0;
        $this->groupConditions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, name="contact_name")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=200, name="contact_phone")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, name="contact_email")
     */
    private $email;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $arrivalDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $departureDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $flightDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $noPassengers;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     **/
    private $flight;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\BookingRequestGroupCondition")
     * @ORM\JoinTable(name="booking_request_group_conditions",
     *      joinColumns={@ORM\JoinColumn(name="booking_request_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="booking_request_group_condition_id", referencedColumnName="id", unique=false)}
     *      )
     */
    private $groupConditions;

    /**
     * @ORM\Column(type="integer", name="confirmed")
     */
    private $confirmed;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getArrivalDate(): ?\DateTimeInterface
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(?\DateTimeInterface $arrivalDate = null): self
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    public function getDepartureDate(): ?\DateTimeInterface
    {
        return $this->departureDate;
    }

    public function setDepartureDate(?\DateTimeInterface $departureDate = null): self
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    public function getFlightDate(): ?\DateTimeInterface
    {
        return $this->flightDate;
    }

    public function setFlightDate(?\DateTimeInterface $flightDate): self
    {
        $this->flightDate = $flightDate;

        return $this;
    }

    public function getNoPassengers(): ?int
    {
        return $this->noPassengers;
    }

    public function setNoPassengers(int $noPassengers): self
    {
        $this->noPassengers = $noPassengers;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return Product
     */
    public function getFlight(): ?Product
    {
        return $this->flight;
    }

    /**
     * @param Product $flight
     */
    public function setFlight(Product $flight): self
    {
        $this->flight = $flight;
        return $this;
    }

    /**
     * Add GroupCondition
     *
     * @param  \App\Entity\BookingRequestGroupCondition $groupCondition
     * @return BookingRequestGroupCondition
     */
    public function addGroupCondition(BookingRequestGroupCondition $groupCondition): BookingRequestGroupCondition
    {
        if(!$this->groupConditions->contains($groupCondition))
        {
            $this->groupConditions[] = $groupCondition;
        }
        return $groupCondition;
    }

    /**
     * Remove GroupCondition
     *
     * @param \App\Entity\BookingRequestGroupCondition $groupCondition
     */
    public function removeGroupCondition(BookingRequestGroupCondition $groupCondition)
    {
        $this->groupConditions->removeElement($groupCondition);
    }

    /**
     * Get GroupConditions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroupConditions()
    {
        return $this->groupConditions;
    }

    /**
     * @return mixed
     */
    public function getConfirmed(): ?int
    {
        return $this->confirmed;
    }

    /**
     * @param mixed $confirmed
     */
    public function setConfirmed($confirmed): self
    {
        $this->confirmed = $confirmed;
        return $this;
    }
}
