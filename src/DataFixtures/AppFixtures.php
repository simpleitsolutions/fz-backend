<?php

namespace App\DataFixtures;

use App\Entity\BookingRequest;
use App\Entity\BookingRequestGroupCondition;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private const FLIGHTS = [
        [
            'name' => 'Classic High',
            'shortName' => 'C-Short',
            'price' => '190.00',
            'advertised' => '1',
            'sortOrder' => '1',
            'confirmed' => '0',
        ],
        [
            'name' => 'Scenic Flight',
            'shortName' => 'S-Short',
            'price' => '190.00',
            'advertised' => '1',
            'sortOrder' => '2',
            'confirmed' => '0',
        ],
        [
            'name' => 'Elite Flight',
            'shortName' => 'E-Short',
            'price' => '190.00',
            'advertised' => '1',
            'sortOrder' => '3',
            'confirmed' => '0',
        ],
        [
            'name' => 'Glacier Flight',
            'shortName' => 'G-Short',
            'price' => '190.00',
            'advertised' => '1',
            'sortOrder' => '4',
            'confirmed' => '0',
        ],
    ];

    private const GROUP_CONDITIONS = [
        [
            'name' => 'Lighter than 50 Kg',
        ],
        [
            'name' => 'Heavier than 90 Kg',
        ],
        [
            'name' => 'Older than 60 Years',
        ],
        [
            'name' => 'Not sporty',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $this->loadFlights($manager);
        $this->loadGroupConditions($manager);
        $this->loadBookingRequests($manager);

        $manager->flush();
    }

    public function loadFlights(ObjectManager $manager)
    {
        $createdDate = new \DateTime();
        foreach (self::FLIGHTS as $userFixture) {
            $product = new Product();
            $product->setDescription($userFixture['name']);
            $product->setShortName($userFixture['shortName']);
            $product->setPrice($userFixture['price']);
            $product->setAdvertised($userFixture['advertised']);
            $product->setSortOrder($userFixture['sortOrder']);
            $product->setCreated($createdDate);

            $this->addReference('flight_'.$userFixture['name'], $product);

            $manager->persist($product);
        }
    }

    public function loadGroupConditions(ObjectManager $manager)
    {
        $createdDate = new \DateTime();
        foreach (self::GROUP_CONDITIONS as $userFixture) {
            $bookingRequestGroupCondition = new BookingRequestGroupCondition();
            $bookingRequestGroupCondition->setName($userFixture['name']);
            $bookingRequestGroupCondition->setCreated($createdDate);

            $this->addReference('group_condition_'.$userFixture['name'], $bookingRequestGroupCondition);

            $manager->persist($bookingRequestGroupCondition);
        }
    }

    public function loadBookingRequests(ObjectManager $manager)
    {
        for ($i = 0; $i < 8; $i++) {
            $bookingRequest = new BookingRequest();
            $bookingRequest->setName("Passenger $i");
            $bookingRequest->setEmail("passenger$i@home.com");
            $bookingRequest->setPhone("079961004$i");
            $bookingRequest->setNoPassengers(rand(0, 4));
            $bookingRequest->setFlight($this->getRandomFlight());
            $bookingRequest->setConfirmed('0');
            $bookingRequest->setCreated(new \DateTime());

            $count = 0;
            $randomNo = rand(0, 3);
            while ($count <= $randomNo)
            {
                $bookingRequest->addGroupCondition($this->getRandomGroupCondition());
                $count++;
            }

            $manager->persist($bookingRequest);
        }
    }

    protected function getRandomFlight(): Product
    {
        $randomFlight = self::FLIGHTS[rand(0, 3)];

        return $this->getReference(
            'flight_'.$randomFlight['name']);
    }

    protected function getRandomGroupCondition(): BookingRequestGroupCondition
    {
        $randomGroupCondition = self::GROUP_CONDITIONS[rand(0, 3)];

        return $this->getReference(
            'group_condition_'.$randomGroupCondition['name']);
    }

}
