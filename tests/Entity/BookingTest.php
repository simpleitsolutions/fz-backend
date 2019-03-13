<?php
/**
 * Created by PhpStorm.
 * User: tomschneider
 * Date: 2019-03-13
 * Time: 21:20
 */

namespace App\Tests\Entity;


use App\Entity\Booking;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;


class BookingTest extends TestCase
{

    public function testConstants()
    {
        $booking = new Booking();
        $this->assertEquals(Booking::STATUS_PAYMENT_FULL, 4);

    }
}
