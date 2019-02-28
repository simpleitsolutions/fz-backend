<?php
namespace App\Controller;

use App\Entity\Booking;
use App\Entity\BookingOwner;
use App\Entity\BookingRequest;
use App\Entity\FlightScheduleTime;
use App\Entity\MeetingLocation;
use App\Entity\Passenger;
use App\Entity\Product;
use App\Entity\Voucher;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class BookingCRUDController extends CRUDController
{
//    public function getNewInstance()
//    {
//        throw new \Exception("HHHKK11");
//        return $instance;
//    }
//        public function createAction(Request $request = NULL)
//    {
//        $result = parent::createAction();
//        $bookingRequestId = $this->getRequest()->get('bookingRequestId', null);
//        $bookingRequestEntityManager = $this->modelManager->getEntityManager(BookingRequest::class);
//        $bookingRequest = $bookingRequestEntityManager->getRepository(BookingRequest::class)->find($bookingRequestId);
//
//        if (!$bookingRequest) {
//            throw $this->createNotFoundException('Unable to find Booking Request.');
//        }
//
//        throw new \Exception("HHH".$bookingRequest->getId()."KK");
//        return $result;
//    }

    public function requestconfirmAction1($id)
    {
//        $request = Request::createFromGlobals();
//        $bookingRequestId = $request->request->get('bookingRequestId');
//        throw new \Exception("HERE1 ".$bookingRequestId);
        $em = $this->getDoctrine()->getManager();
        $booking_request = $em->getRepository(BookingRequest::class)->find($id);
        $flightScheduleTimeRepos = $this->getDoctrine()->getRepository(FlightScheduleTime::class);
        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        $meetingLocationRepository = $this->getDoctrine()->getRepository(MeetingLocation::class);
        $bookingOwnerRepository = $this->getDoctrine()->getRepository(BookingOwner::class);

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
        $flyZermattOwner = $bookingOwnerRepository->findOneBy(array('name' => 'FlyZermatt'));
        $booking->setOwner($flyZermattOwner);
        $booking->setNotes($notes);
        $booking->setContactinfo($booking_request->getPhone().' '.$booking_request->getEmail());

        $flightScheduleTimes = $flightScheduleTimeRepos->getFlightScheduleTimesFor($booking_request->getArrivaldate());
        $preferredFlights = $productRepository->getPreferredFlightProducts();
        $preferredMeetingLocations = $meetingLocationRepository->getPreferredMeetingLocations();
//        $form = $this->createForm(new BookingType($flightScheduleTimes, $preferredFlights, $preferredMeetingLocations), $booking);
//        $form = $this->createForm(BookingType::class, $booking, array(
//                'flightScheduleTimes' => $flightScheduleTimes,
//                'preferredFlights' => $preferredFlights,
//                'preferredMeetingLocations' => $preferredMeetingLocations)
//        );

//        $form->add('saveAndExit', SubmitType::class, array('label' => 'Save', 'attr' => ['class' => 'form-control']));
//        $form->add('saveAndConfirm', SubmitType::class, array('label' => 'Save & Confirm', 'attr' => ['class' => 'form-control']));
//        $form->add('cancel', SubmitType::class, array('attr' => array('class' => 'form-control', 'formnovalidate' => true, 'data-toggle' => 'modal', 'data-target' => '#cancelWarning', )));

//        $form->handleRequest($request);

//        if($form->get('cancel')->isClicked())
//        {
//            return $this->redirect($this->generateUrl('booking_request_index'));
//        }

//        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($booking->getPassengers() as $passenger)
            {
                $passenger->setFlight($booking->getFlight());
            }

            $booking->setCreatedBy($this->getUser());
            $booking->setLastUpdatedBy($this->getUser());

//            if($form->get('saveAndConfirm')->isClicked())
//            {
//                $booking->setStatus(Booking::STATUS_CONFIRMED);
//            }

//            $em = $this->getDoctrine()->getManager();
//            $em->persist($booking);

            //TODO Set the Booking request to confirmed once the Booking has been persisted
//            $booking_request->setConfirmed(true);
//            $em->persist($booking_request);
//            $em->flush();
//            $this->get('session')->getFlashBag()->add('message', 'Booking Request has been successfully confirmed!');

//            $nextAction = $form->get('saveAndExit')->isClicked()
//                ? $this->generateUrl('booking_show', array('id' => $booking->getId()))
//                : $this->generateUrl('booking_update', array ('id'=> $booking->getId()));
//            return $this->redirect($nextAction);
//        }

//        throw new \Exception("HERE ".$this->generateUrl('booking_create', ['booking' => $booking]) );
        return new RedirectResponse($this->admin->generateUrl('create', ['bookingRequestId' => $id]));

//        return $this->render('booking\edit.html.twig', array('entity' => $booking, 'form' => $form->createView()));
    }

}
