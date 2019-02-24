<?php
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Route\RouteCollection;

final class BookingScheduleViewAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = 'booking_schedule_view';

    protected $baseRouteName = 'booking_schedule_view';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
    }
}
