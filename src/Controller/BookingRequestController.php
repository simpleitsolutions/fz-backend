<?php
namespace App\Controller;

use App\Entity\BookingRequest;
use App\Form\BookingType;
use App\Entity\Booking;
use App\Entity\Passenger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Psr\Log\LoggerInterface;

/**
 * @Route("/booking/request")
 */
class BookingRequestController extends AbstractController
{

    /**
     * @Route("/add", name="booking_request_add", methods={"POST"})
     */
    public function add(Request $request, LoggerInterface $logger)
    {
        $logger->error("******************************** APP-ENV: ".$_SERVER['APP_ENV'], ["doctrine"]);

        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');
        $bookingRequest = $serializer->deserialize($request->getContent(), BookingRequest::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($bookingRequest);
        $em->flush();

        return $this->json($bookingRequest);
    }

    /**
     * @Route("/confirm/{id}", name="booking_request_confirm")
     */
    public function confirmAction($id)
    {
        $request = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $booking_request = $em->getRepository('App\Entity\BookingRequest')->find($id);
        $flightScheduleTimeRepos = $this->getDoctrine()->getRepository('App\Entity\FlightScheduleTime');
        $productRepository = $this->getDoctrine()->getRepository('App\Entity\Product');
        $meetingLocationRepository = $this->getDoctrine()->getRepository('App\Entity\MeetingLocation');

        if (!$booking_request) {
            throw $this->createNotFoundException('Unable to find Booking Request.');
        }
        $booking = new Booking();
        $i=0;
        while($i++ < $booking_request->getNoPassengers())
        {
            $passenger = new Passenger();
            if($i==1)
            {
                $passenger->setName($booking_request->getName());
            } else {
                $passenger->setName('Passenger '.$i);
            }
            $booking->addPassenger($passenger);
        }

        $booking->setFlight($booking_request->getFlight());

        if ($booking_request->getFlightdate() != null) {
            $booking->setFlightDate($booking_request->getFlightdate());
        } else if ($booking_request->getArrivaldate() != null) {
            $booking->setFlightDate($booking_request->getArrivaldate());
        } else {
            $now = new \DateTime();
            $booking->setFlightDate(new \DateTime($now->format('Y-m-d')));
        }
        $notes = $booking_request->getComments();
        foreach ($booking_request->getGroupConditions() as $groupCondition)
        {
            $notes = $notes.', '.$groupCondition->getName();
        }
        $booking->setNotes($notes);
        $booking->setContactinfo($booking_request->getPhone().' '.$booking_request->getEmail());

        $flightScheduleTimes = $flightScheduleTimeRepos->getFlightScheduleTimesFor($booking_request->getArrivaldate());
        $preferredFlights = $productRepository->getPreferredFlightProducts();
        $preferredMeetingLocations = $meetingLocationRepository->getPreferredMeetingLocations();
//        $form = $this->createForm(new BookingType($flightScheduleTimes, $preferredFlights, $preferredMeetingLocations), $booking);
        $form = $this->createForm(BookingType::class, $booking, array(
            'flightScheduleTimes' => $flightScheduleTimes,
            'preferredFlights' => $preferredFlights,
            'preferredMeetingLocations' => $preferredMeetingLocations));

        // $form->add('save', 'submit', array('label' => 'Confirm'));
        $form->add('saveAndExit', SubmitType::class, array('label' => 'Save'));
        $form->add('saveAndConfirm', SubmitType::class, array('label' => 'Save & Confirm'));
        $form->add('cancel', SubmitType::class, array('attr' => array('formnovalidate' => true, 'data-toggle' => 'modal', 'data-target' => '#cancelWarning', )));

        $form->handleRequest($request);

        if($form->get('cancel')->isClicked())
        {
            return $this->redirect($this->generateUrl('booking_request_index'));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($booking->getPassengers() as $passenger)
            {
                $passenger->setFlight($booking->getFlight());
            }

            $booking->setCreatedBy($this->getUser());
            $booking->setLastUpdatedBy($this->getUser());

            if($form->get('saveAndConfirm')->isClicked())
            {
                $booking->setStatus(Booking::STATUS_CONFIRMED);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($booking);

            $booking_request->setConfirmed(true);
            $em->persist($booking_request);
            $em->flush();

            $this->get('session')->getFlashBag()->add('message', 'Booking Request has been successfully confirmed!');

            $nextAction = $form->get('saveAndExit')->isClicked()
                ? $this->generateUrl('booking_show', array('id' => $booking->getId()))
                : $this->generateUrl('booking_update', array ('id'=> $booking->getId()));
            return $this->redirect($nextAction);
        }
        return $this->render('booking\edit.html.twig', array('entity' => $booking, 'form' => $form->createView()));
    }

}
