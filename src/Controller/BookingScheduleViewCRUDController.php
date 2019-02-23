<?php
namespace App\Controller;

use App\Form\DateSelectorType;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use App\Helper\BookingDailySchedule;

class BookingScheduleViewCRUDController extends CRUDController
{
    public function listAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        $reposBooking = $this->getDoctrine()->getRepository('App\Entity\Booking');
        $reposWaitingList = $this->getDoctrine()->getRepository('App\Entity\WaitingListItem');
        $reposBookingRequest = $this->getDoctrine()->getRepository('App\Entity\BookingRequest');
        $flightScheduleRepos = $this->getDoctrine()->getRepository('App\Entity\FlightSchedule');
        $reposPilot = $this->getDoctrine()->getRepository('App\Entity\Pilot');
        $reposPurchase = $this->getDoctrine()->getRepository('App\Entity\Purchase');

        $pilot = $reposPilot->find(1);
        $startDate = new \DateTime();
        $startDate->modify('-40 days');
        $endDate = new \DateTime();
        $purchases = $reposPurchase->getAllFlightsForPilotOverDateRange($pilot, $startDate, $endDate);

        $filter = $em->getFilters()->enable('softdeleteable');
        $filter->disableForEntity('App\Entity\WaitingListItem');

        $dateStr = $request->query->get('date', '');
        if($dateStr == '')
        {
            if($session->get('current_date') == '')
            {
                $indexdate = new \DateTime();
            }
            else
            {
                $indexdate = new \DateTime($session->get('current_date'));
            }
        }
        else
        {
            $indexdate = new \DateTime($dateStr);
        }
        $session->set('current_date', $indexdate->format('Y-m-d'));
//         $bookingrequests_enddate = clone $indexdate;
//         $bookingrequests_enddate->modify('+ 2 months');


        $defaultData = array();
        $defaultData['targetDate'] = $indexdate->format("d-m-Y");
        $dateForm = $this->createForm(DateSelectorType::class, $defaultData);
        $dateForm->handleRequest($request);

        $flightSchedule = $flightScheduleRepos->getFlightScheduleForDate($indexdate);
        $pilots = $reposPilot->getFlyZermattPilots();
        $bookings = $reposBooking->getBookingsForDate($indexdate);
        $waitingList = $reposWaitingList->getWaitingListForDate($indexdate);
        $bookingRequests = $reposBookingRequest->getBookingRequestsForDate($indexdate);
//        throw new \Exception("HERE".sizeof($bookings));

        if($flightSchedule === null)
        {
            $this->get('session')->getFlashBag()->add('danger', 'No Flight Schedule active on this date');
            $bookingDailySchedule = new BookingDailySchedule($pilots);
        }
        else
        {
            $bookingDailySchedule = new BookingDailySchedule($pilots);
            $bookingDailySchedule->generateSchedule($indexdate, $bookings, $flightSchedule);
            $bookingDailySchedule->setWaitingList($waitingList);
            $bookingDailySchedule->setBookingRequests($bookingRequests);
        }

//        return $this->render('AazpBookingBundle:Booking:index.schedule.html.twig', array(
//            'bookingDailySchedule' => $bookingDailySchedule,
//            'bookings' => $bookings,
//            'indexdate' => $indexdate,
//            'dateForm' => $dateForm->createView(),
//            'purchases' => $purchases,
//        ));


//        return $this->render('AppBundle:Booking:index.html.twig', array(
//            'entities' => $bookings,
//            'indexdate' => $indexdate,
//            'waitingList' => $waitingList,
//            'booking_requests_today' => $booking_requests_today,
//            'report' => $report,
//        ));

        return $this->renderWithExtraParams('admin/booking-schedule-view.html.twig', array(
            'bookingDailySchedule' => $bookingDailySchedule,
            'bookings' => $bookings,
            'indexdate' => $indexdate,
            'dateForm' => $dateForm->createView(),
            'purchases' => $purchases,
        ));
    }
}
