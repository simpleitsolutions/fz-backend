<?php
namespace App\Controller;

use App\Entity\BookingOwner;
use App\Entity\BookingRequest;
use App\Entity\FlightScheduleTime;
use App\Entity\MeetingLocation;
use App\Entity\Product;
use App\Form\BookingType;
use App\Entity\Booking;
use App\Entity\Passenger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
     * @Route("/confirm/{id}", name="booking_request_custom_confirm")
     */
    public function confirmAction($id)
    {
        $request = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $bookingRequest = $em->getRepository(BookingRequest::class)->find($id);
        $flightScheduleTimeRepos = $this->getDoctrine()->getRepository(FlightScheduleTime::class);
        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        $meetingLocationRepository = $this->getDoctrine()->getRepository(MeetingLocation::class);
        $bookingOwnerRepository = $this->getDoctrine()->getRepository(BookingOwner::class);

        if (!$bookingRequest) {
            throw $this->createNotFoundException('Unable to find Booking Request.');
        }
        $booking = new Booking();
        $i=0;
        while($i++ < $bookingRequest->getNoPassengers())
        {
            $passenger = new Passenger();
            if($i==1)
            {
                $passenger->setName($bookingRequest->getName());
            } else {
                $passenger->setName('Passenger '.$i);
            }
            $booking->addPassenger($passenger);
        }

        $booking->setFlight($bookingRequest->getFlight());

        if ($bookingRequest->getFlightdate() != null) {
            $booking->setFlightDate($bookingRequest->getFlightdate());
        } else if ($bookingRequest->getArrivaldate() != null) {
            $booking->setFlightDate($bookingRequest->getArrivaldate());
        } else {
            $now = new \DateTime();
            $booking->setFlightDate(new \DateTime($now->format('Y-m-d')));
        }
        $notes = $bookingRequest->getComments();
        foreach ($bookingRequest->getGroupConditions() as $groupCondition)
        {
            $notes = $notes.'--[ '.$groupCondition->getName().' ]';
        }
        $flyZermattOwner = $bookingOwnerRepository->findOneBy(array('name' => 'FlyZermatt'));
        $booking->setOwner($flyZermattOwner);
        $booking->setNotes($notes);
        $booking->setContactinfo($bookingRequest->getPhone().' '.$bookingRequest->getEmail());

        $flightScheduleTimes = $flightScheduleTimeRepos->getFlightScheduleTimesFor($bookingRequest->getArrivaldate());
        $preferredFlights = $productRepository->getPreferredFlightProducts();
        $preferredMeetingLocations = $meetingLocationRepository->getPreferredMeetingLocations();
//        $form = $this->createForm(new BookingType($flightScheduleTimes, $preferredFlights, $preferredMeetingLocations), $booking);
        $form = $this->createForm(BookingType::class, $booking, array(
            'flightScheduleTimes' => $flightScheduleTimes,
            'preferredFlights' => $preferredFlights,
            'preferredMeetingLocations' => $preferredMeetingLocations)
        );
        $form->remove('flightScheduleTime');
        $form->add('bookingRequestId',HiddenType::class, ['data' => $bookingRequest->getId(), 'mapped' => false]);
        $form->add('saveAndExit', SubmitType::class, ['label' => 'Save', 'attr' => ['class' => 'btn btn-success']]);
        $form->add('saveAndConfirm', SubmitType::class, ['label' => 'Save & Confirm', 'attr' => ['class' => 'btn btn-success']]);
        $form->add('cancel', ButtonType::class, ['attr' => ['class' => 'btn btn-danger', 'formnovalidate' => true,]]);

        $form->handleRequest($request);

//        if($form->get('cancel')->isClicked())
//        {
//            return $this->redirect($this->generateUrl('booking_custom_schedule'));
//        }

        if ($form->isSubmitted() && $form->isValid())
        {

            foreach ($booking->getPassengers() as $passenger)
            {
                $passenger->setFlight($booking->getFlight());
            }

            $booking->setCreatedBy($this->getUser());
            $booking->setLastUpdatedBy($this->getUser());

            $flashMessage = 'Booking has been created successfully !';

            if($form->get('saveAndConfirm')->isClicked())
            {
                $booking->setStatus(Booking::STATUS_CONFIRMED);
                $flashMessage = 'Booking has been created and confirmed successfully !';
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($booking);

            $bookingRequest->setConfirmed(true);
            $em->persist($bookingRequest);
            $em->flush();

            $this->addFlash('sonata_flash_success', $flashMessage);

            return $this->redirect($this->generateUrl('booking_custom_show', array('id' => $booking->getId())));
        }
        return $this->render('booking\new.html.twig', array('entity' => $booking, 'form' => $form->createView()));
    }

}
