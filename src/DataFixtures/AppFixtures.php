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
        ],
        [
            'name' => 'Scenic Flight',
        ],
        [
            'name' => 'Elite Flight',
        ],
        [
            'name' => 'Glacier Flight',
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
        foreach (self::FLIGHTS as $userFixture) {
            $product = new Product();
            $product->setName($userFixture['name']);

            $this->addReference('flight_'.$userFixture['name'], $product);

            $manager->persist($product);
        }
    }

    public function loadGroupConditions(ObjectManager $manager)
    {
        foreach (self::GROUP_CONDITIONS as $userFixture) {
            $bookingRequestGroupCondition = new BookingRequestGroupCondition();
            $bookingRequestGroupCondition->setName($userFixture['name']);

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
