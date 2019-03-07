<?php
namespace App\Controller;

use App\Entity\Availability;
use App\Entity\FlightSchedule;
use App\Entity\Pilot;
use DateTime;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/availability")
 */
class AvailabilityController extends AbstractController
{


    /**
     * @Route("/availability/create", name="availability_custom_create")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $pilotRepos = $this->getDoctrine()->getRepository(Pilot::class);

        $targetDate = $request->get('targetDate', null);
        $pilotId = $request->get('pilotId', null);

        $unavailableFlightDate = DateTime::createFromFormat('Y-m-d', $targetDate);
        $pilot = $pilotRepos->find($pilotId);

        $availability = new Availability();
        $availability->setPilot($pilot);
        $availability->setUnavailableFlightDate($unavailableFlightDate);

        $em->persist($availability);
        $em->flush();

        return $this->redirect($this->generateUrl('availability_edit', ['id' => $availability->getId()]));
    }

    public function pilotAvailabilityDateSelectAction()
    {
        $request = Request::createFromGlobals();
        $session = new Session();

//         throw new \Exception("HRE");
        $defaultData = array();
//         $defaultData['targetDate'] = $currentDate->format("d-m-Y");
        $dateForm = $this->createForm(new DateSelectorType(), $defaultData, array(
            'action' => $this->generateUrl('pilot_availability_date_select'),
            'method' => 'GET',
        ));
        $dateForm->handleRequest($request);
        
        if ($dateForm->isValid())
        {
            $currentDateStr = $dateForm['targetDate']->getData();
            $scheduleDate = \DateTime::createFromFormat('d-m-Y', $currentDateStr);
        
            $session->set('current_date', $scheduleDate->format("Y-m-d"));
        }
        
        return $this->redirect($this->generateUrl('pilot_availability', array('date' => $scheduleDate->format('Y-m-d'))));
    }

    public function manageAvailability($pilotId, $targetDateStr)
    {
        $availabilityRepos = $this->getDoctrine()->getRepository(Availability::class);
        $reposPilot = $this->getDoctrine()->getRepository(Pilot::class);
        $flightScheduleRepos = $this->getDoctrine()->getRepository(FlightSchedule::class);

        $pilot = $reposPilot->find($pilotId);
        $targetDate = \DateTime::createFromFormat('Y-m-d', $targetDateStr);
        $flightSchdule = $flightScheduleRepos->getFlightScheduleForDate($targetDate);

//        throw new \Exception("HERE ".$pilot->getId());
        $availability = $availabilityRepos->getAvailabilityForPilotOnDate($pilot, $targetDate);

        return $this->render(
            'availability/manage.html.twig',
            ['availability' => $availability,
                'pilot'=> $pilot,
                'targetDate' => $targetDate,
                'flightSchedule' => $flightSchdule]
        );
    }

    public function pilotAvailabilityAction()
    {
        $request = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $availabilityRepos = $this->getDoctrine()->getRepository('AazpBookingBundle:Availability');
        $flightTimeRepos = $this->getDoctrine()->getRepository('AazpBookingBundle:FlightScheduleTime');
        $user = $this->container->get('security.context')->getToken()->getUser();
        $pilot = $user->getPilot();
        $session = new Session();

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

        $defaultData = array();
        $defaultData['targetDate'] = $indexdate->format("d-m-Y");
        $dateForm = $this->createForm(new DateSelectorType(), $defaultData, array(
            'action' => $this->generateUrl('pilot_availability_date_select'),
            'method' => 'GET',
        ));
        $dateForm->handleRequest($request);
        
        $scheduleDate = \DateTime::createFromFormat('Y-m-d', $indexdate->format("Y-m-d"));
        $availability = $availabilityRepos->getAvailabilityForPilotOnDate($pilot, $scheduleDate);
        
        if($availability === null)
        {
            $availability = new Availability();
            $availability->setUnavailableFlightDate($scheduleDate);
            $availability->setPilot($pilot);
            $pilot->addAvailability($availability);
        }
        
        $flightTimes = $flightTimeRepos->getFlightScheduleTimesFor($availability->getUnavailableFlightDate());
        
        $allDay = false;
        if(sizeof($flightTimes) == sizeof($availability->getFlightScheduleTimes()))
        {
            $allDay = true;
        }
        $form = $this->createForm(new PilotAvailabilityType($flightTimes, $allDay), $availability);
        $form->add('save', 'submit', array('label' => 'Save'));
        $form->add('cancel', 'submit', array('attr' => array('formnovalidate' => true, 'data-toggle' => 'modal', 'data-target' => '#modalWarning', 'data-modal-title' => 'Are you want to cancel this Availability?')));
        $form->handleRequest($request);

        if($form->isValid())
        {
            $currentDate = $availability->getUnavailableFlightDate();
            //         throw new \Exception("HERE..".$currentDate->format('d-m-Y'));
            $em->persist($availability);
            $em->flush();
        
            $defaultData = array();
            $defaultData['targetDate'] = $currentDate->format("d-m-Y");
            $dateForm = $this->createForm(new DateSelectorType(), $defaultData);
            $dateForm->handleRequest($request);
        
            $this->addFlash('sonata_flash_success', 'Flight Availability updated!');
        
            $session->set('current_date', $currentDate->format("Y-m-d"));
            return $this->redirect($this->generateUrl('pilot_availability'));
        }
        
        return $this->render('AazpBookingBundle:Pilot:availability.html.twig', array(
            'dateForm' => $dateForm->createView(),
            'form'   => $form->createView(),
        ));
    }
    
//     public function pilotAvailabilityAction()
//     {
//         $request = Request::createFromGlobals();
//         $em = $this->getDoctrine()->getManager();
//         $availabilityRepos = $this->getDoctrine()->getRepository('AazpBookingBundle:Availability');
//         $flightTimeRepos = $this->getDoctrine()->getRepository('AazpBookingBundle:FlightScheduleTime');
//         $user = $this->container->get('security.context')->getToken()->getUser();
//         $pilot = $user->getPilot();
//         $session = new Session();

//         if($session->has('current_date'))
//         {
// //             $currentDate = $session->get('current_date');
//             $currentDate = new \DateTime($session->get('current_date'));
//         }
//         else
//         {
// //             $currentDateStr = date("Y-m-d");
//             $currentDate = new \DateTime();
//         }
//         $session->set('current_date', $currentDate->format('Y-m-d'));
        
// //         throw new \Exception("HRE ".$currentDate);
        
//         $defaultData = array();
//         $defaultData['targetDate'] = $currentDate->format("d-m-Y");
//         $dateForm = $this->createForm(new DateSelectorType(), $defaultData);
//         $dateForm->handleRequest($request);

//         $scheduleDate = \DateTime::createFromFormat('Y-m-d', $currentDate->format("Y-m-d"));
//         $availability = $availabilityRepos->getAvailabilityForPilotOnDate($pilot, $currentDate);

//         if($availability === null)
//         {
//             $availability = new Availability();
//             $availability->setUnavailableFlightDate($currentDate);
//             $availability->setPilot($pilot);
//             $pilot->addAvailability($availability);
//         }

//         $flightTimes = $flightTimeRepos->getFlightScheduleTimesFor($availability->getUnavailableFlightDate());

//         $allDay = false;
//         if(sizeof($flightTimes) == sizeof($availability->getFlightScheduleTimes()))
//         {
//             $allDay = true;
//         }
//         $form = $this->createForm(new PilotAvailabilityType($flightTimes, $allDay), $availability);
//         $form->add('save', 'submit', array('label' => 'Save'));
//         $form->add('cancel', 'submit', array('attr' => array('formnovalidate' => true, 'data-toggle' => 'modal', 'data-target' => '#cancelWarning', )));
//         $form->handleRequest($request);

// //         throw new \Exception("HERE...".$dateForm->isValid()."-".$form->isValid());
//         if ($dateForm->isValid())
//         {
//             $currentDateStr = $dateForm['targetDate']->getData();
//             $scheduleDate = \DateTime::createFromFormat('d-m-Y', $currentDateStr);
            
// //             throw new \Exception("HERE..".$currentDateStr);
//             $availability = $availabilityRepos->getAvailabilityForPilotOnDate($pilot, $scheduleDate);
//             if($availability === null)
//             {
//                 $availability = new Availability();
//                 $availability->setUnavailableFlightDate($scheduleDate);
//                 $availability->setPilot($pilot);
//                 $pilot->addAvailability($availability);
//             }
// //             throw new \Exception("HERE1..".$availability->getId()." -- ".$availability->getUnavailableFlightDate()->format('d-m-Y'));
            
//             $flightTimes = $flightTimeRepos->getFlightScheduleTimesFor($availability->getUnavailableFlightDate());
            
//             $allDay = false;
//             if(sizeof($flightTimes) == sizeof($availability->getFlightScheduleTimes()))
//             {
//                 $allDay = true;
//             }
//             $form = $this->createForm(new PilotAvailabilityType($flightTimes, $allDay), $availability);
//             $form->add('save', 'submit', array('label' => 'Save'));
//             $form->add('cancel', 'submit', array('attr' => array('formnovalidate' => true, 'data-toggle' => 'modal', 'data-target' => '#cancelWarning', )));
//             $form->handleRequest($request);
            
//             $session->set('current_date', $currentDate->format("Y-m-d"));
            
//             return $this->render('AazpBookingBundle:Pilot:availability.html.twig', array(
//              'dateForm' => $dateForm->createView(),
//              'form'   => $form->createView(),
//             ));
//         }
        
//         if($form->isValid())
//         {
//             $currentDate = $availability->getUnavailableFlightDate();
// //         throw new \Exception("HERE..".$currentDate->format('d-m-Y'));
//             $em->persist($availability);
//             $em->flush();
        
//             $defaultData = array();
//             $defaultData['targetDate'] = $currentDate->format("d-m-Y");
//             $dateForm = $this->createForm(new DateSelectorType(), $defaultData);
//             $dateForm->handleRequest($request);

//             $this->addFlash('sonata_flash_success', 'Flight Availability updated!');
            
//             $session->set('current_date', $currentDate->format("Y-m-d"));
//             return $this->redirect($this->generateUrl('pilot_availability'));
//         }
        
//         return $this->render('AazpBookingBundle:Pilot:availability.html.twig', array(
//             'dateForm' => $dateForm->createView(),
//             'form'   => $form->createView(),
//         ));
//     }

}
?>
