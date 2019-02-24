<?php
namespace App\Controller;
 
use App\Entity\Booking;
use App\Entity\Passenger;
use App\Entity\Payment;
use App\Entity\PaymentType;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\PurchaseItem;
use App\Form\BPPassengerType;
use App\Form\DateSelectorType;
use App\Form\PurchaseType;
use App\Helper\BookingPaymentHelper;
use App\Helper\PaymentViewHelper;
use Doctrine\Common\Collections\ArrayCollection;
//use Sonata\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
* @Route("/booking")
*/
class BookingController extends AbstractController
{
//    public function indexAction(Request $request)
//    {
//    	$session = $request->getSession();
//        $em = $this->getDoctrine()->getManager();
//        $reposBooking = $this->getDoctrine()->getRepository('AazpBookingBundle:Booking');
//        $reposWaitingList = $this->getDoctrine()->getRepository('AazpBookingBundle:WaitingListItem');
//        $reposBookingRequest = $this->getDoctrine()->getRepository('AazpBookingBundle:BookingRequest');
//
//		$filter = $em->getFilters()->enable('softdeleteable');
//		$filter->disableForEntity('Aazp\BookingBundle\Entity\WaitingListItem');
//
//    	$report = $request->query->get('report', false);
//
//    	$dateStr = $request->query->get('date', '');
//		if($dateStr == '')
//		{
//			if($session->get('current_date') == '')
//			{
//				$indexdate = new \DateTime();
//			}
//			else
//			{
//				$indexdate = new \DateTime($session->get('current_date'));
//			}
//		}
//		else
//		{
//			$indexdate = new \DateTime($dateStr);
//		}
//		$session->set('current_date', $indexdate->format('Y-m-d'));
//
//		$bookings = $reposBooking->getBookingsForDate($indexdate);
//		$waitingList = $reposWaitingList->getWaitingListForDate($indexdate);
//		$booking_requests_today = $reposBookingRequest->getBookingRequestsForDate($indexdate);
//
//        return $this->render('AazpBookingBundle:Booking:index.html.twig', array(
//            'entities' => $bookings,
//            'indexdate' => $indexdate,
//            'waitingList' => $waitingList,
//            'booking_requests_today' => $booking_requests_today,
//            'report' => $report,
//        ));
//    }

    /**
     * @Route("/dateselect", name="booking_date_select")
     */
    public function dateSelectorAction(Request $request)
    {
        $session = $request->getSession();

        if($session->get('current_date') == '')
        {
            $targetDate = new \DateTime();
        }
        else
        {
            $targetDate = new \DateTime($session->get('current_date'));
        }
        
        $defaultData = array();
        $defaultData['targetDate'] = $targetDate->format("d-m-Y");
        $dateForm = $this->createForm(DateSelectorType::class, $defaultData);
        $dateForm->handleRequest($request);

        if($dateForm->isValid())
        {
            $targetDateStr = $dateForm->get('targetDate')->getData();
            $targetDate = \DateTime::createFromFormat('d-m-Y', $targetDateStr);
            $session->set('current_date', $targetDate->format('Y-m-d'));
        }

        return $this->redirect($this->generateUrl('booking_schedule_view_list', array('date' => $targetDate->format('Y-m-d'))));
    }


//    public function recentIndexAction()
//    {
//        $repos = $this->getDoctrine()->getRepository('AazpBookingBundle:Booking');
//
//		$fromDate = new \DateTime();
//		$bookings = $repos->getBookingsForTheLastDays($fromDate, 7);
//
//        return $this->render('AazpBookingBundle:Booking:recent.html.twig', array(
//            'bookings' => $bookings,
//            'fromDate' => $fromDate
//        ));
//    }
//
    /**
     * @Route("/show/{id}", name="booking_schedule_show")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $paymentsRepos = $em->getRepository('\App\Entity\Payment');

        $booking = $em->getRepository('\App\Entity\Booking')->find($id);

        if (!$booking) {
            throw $this->createNotFoundException('Unable to find Booking entity.');
        }

        $deleteForm = $this->createFormBuilder($booking)
		            ->setAction($this->generateUrl('booking_delete', array ('id' => $id)))
		            ->add('Delete', SubmitType::class)
					->getForm();

		$booking->hasPassengerPayment();

		$payments = $paymentsRepos->getPaymentsForBooking($booking);
		$paymentViewHelper = new PaymentViewHelper();
		$paymentViewList = $paymentViewHelper->processPayments($payments);

// 		$payments = new ArrayCollection();
// 		foreach($booking->getPassengers() as $passenger)
// 		{
// 			if($passenger->getPurchase() != null)
// 			{
// 				foreach($passenger->getPurchase()->getPayments() as $payment)
// 				{
// 					if(!$payments->contains($payment))
// 					{
// 						$payments[] = $payment;
// 					}
// 				}
// 			}
// 		}

        return $this->render('booking/show.html.twig', array(
            'entity'      => $booking,
            'delete_form' => $deleteForm->createView(),
            'payments' => $payments,
            'paymentViewList' => $paymentViewList
        ));
    }

//    public function newAction(Request $request)
//    {
//        $flightScheduleTimeRepos = $this->getDoctrine()->getRepository('AazpBookingBundle:FlightScheduleTime');
//        $productRepository = $this->getDoctrine()->getRepository('AazpBookingBundle:Product');
//        $meetingLocationRepository = $this->getDoctrine()->getRepository('AazpBookingBundle:MeetingLocation');
//        $bookingOwnerRepository = $this->getDoctrine()->getRepository('AazpBookingBundle:BookingOwner');
//
//        $booking = new Booking();
//		$booking->setCreatedBy($this->getUser());
//		$booking->setLastUpdatedBy($this->getUser());
//		$date = new \DateTime();
//		$dateStr = $request->query->get('date', $date->format('Y-m-d'));
//		$date = new \DateTime($dateStr);
//
//		$flightScheduleTimeId = $request->query->get('flightScheduleTimeId', null);
//		if($flightScheduleTimeId != null || $flightScheduleTimeId != '')
//		{
//		    $flightScheduleTime = $flightScheduleTimeRepos->findOneById($flightScheduleTimeId);
//		    $booking->setFlightScheduleTime($flightScheduleTime);
//		    $booking->setMeetingTime($flightScheduleTime->getScheduleStartTime());
//        }
//
//		$booking->setFlightdate($date);
//		$booking->addPassenger(new Passenger());
//
//		$flightScheduleTimes = $flightScheduleTimeRepos->getFlightScheduleTimesFor($date);
//
//		$preferredFlights = $productRepository->getPreferredFlightProducts();
//		$preferredMeetingLocations = $meetingLocationRepository->getPreferredMeetingLocations();
//		$defaultOwner = $bookingOwnerRepository->findOneBy(array('name' => 'FlyZermatt'));
//		$booking->setOwner($defaultOwner);
//
//		$form = $this->createForm(new BookingType($flightScheduleTimes, $preferredFlights, $preferredMeetingLocations), $booking);
//		$form->remove('flightScheduleTime');
//		$form->add('save', 'submit');
//		$form->add('saveAndExit', 'submit', array('label' => 'Save'));
//		$form->add('saveAndConfirm', 'submit', array('label' => 'Confirm'));
// 		$form->add('cancel', 'submit', array('attr' => array('formnovalidate' => true, 'data-toggle' => 'modal', 'data-target' => '#cancelWarning',)));
//
//		$form->handleRequest($request);
//
//		if($form->get('cancel')->isClicked())
//		{
//			return $this->redirect($this->generateUrl('booking_index_schedule'));
//		}
//
//	    if ($form->isValid())
//	    {
//	        foreach ($flightScheduleTimes as $flightScheduleTime)
//	        {
//	            if($booking->getMeetingTime()->format('H:i') >= $flightScheduleTime->getScheduleStartTime()->format('H:i') &&
//	                $booking->getMeetingTime()->format('H:i') <= $flightScheduleTime->getScheduleEndTime()->format('H:i') &&
//	                $booking->getFlightScheduleTime() === null )
//	            {
//	               $booking->setFlightScheduleTime($flightScheduleTime);
//	            }
//	        }
//
//	    	$em = $this->getDoctrine()->getManager();
//		    $em->persist($booking);
//
//			foreach ($booking->getPassengers() as $passenger)
//			{
//				$passenger->setFlight($booking->getFlight());
//			}
//
//			if($form->get('saveAndConfirm')->isClicked())
//			{
//				$booking->setStatus(Booking::STATUS_CONFIRMED);
//			}
//
//		    $em->flush();
//
//			$this->get('session')->getFlashBag()->add('success', 'New Booking has been successfully created!');
//			$this->get('session')->set('current_date', $booking->getFlightDate()->format('Y-m-d'));
//
//			$nextAction = ($form->get('saveAndExit')->isClicked() or $form->get('saveAndConfirm')->isClicked())
//			        ? $this->generateUrl('booking_show', array('id'=> $booking->getId()))
//			        : $this->generateUrl('booking_new');
//	        return $this->redirect($nextAction);
//	    }
//		return $this->render('AazpBookingBundle:Booking:new.html.twig', array('form' => $form->createView()));
//    }
//
//    public function quickBookingAction(Request $request)
//    {
//    	$em = $this->getDoctrine()->getManager();
//		$flight = $em->getRepository('AazpBookingBundle:Product')->find(10); //See Comments
//		$meeting_location = $em->getRepository('AazpBookingBundle:MeetingLocation')->find(12); //See Comments
//		$bookingOwner = $em->getRepository('AazpBookingBundle:BookingOwner')->findByName("FlyZermatt")[0];
//
//		if (!$flight) {
//            throw $this->createNotFoundException('Unable to find Flight entity.');
//        }
//		if (!$meeting_location) {
//            throw $this->createNotFoundException('Unable to find Meeting Location entity.');
//        }
//
//		$booking = new Booking();
//		$booking->setCreatedBy($this->getUser());
//		$booking->setLastUpdatedBy($this->getUser());
//		$date = new \DateTime();
//		$booking->setFlightdate($date);
//		$booking->setNotes('Quick Booking by '.$this->getUser()->getUsernameCanonical());
//
//		$form = $this->createForm(new BookingQuickType(), $booking);
//		$form->add('saveAndExit', 'submit', array('label' => 'Save'));
// 		$form->add('cancel', 'submit', array('attr' => array('formnovalidate' => true, 'data-toggle' => 'modal', 'data-target' => '#cancelWarning', )));
//
//		$form->handleRequest($request);
//
//		if($form->get('cancel')->isClicked())
//		{
//			return $this->redirect($this->generateUrl('booking_index_schedule'));
//		}
//
//	    if ($form->isValid()) {
//			$booking->setMeetingLocation($meeting_location);
//			$booking->setFlight($flight);
//			$booking->setOwner($bookingOwner);
//
//			$noofpassengers = $form->get('noofpassengers')->getData();
//			for($i=1; $i <= $noofpassengers; $i++)
//			{
//				$passenger = new Passenger();
//				$passenger->setName('Passenger '.$i);
//				$booking->addPassenger($passenger);
//				$passenger->setFlight($flight);
//			}
//
//		    $em->persist($booking);
//		    $em->flush();
//
//			$this->get('session')->getFlashBag()->add('success', 'Quick Booking has been successfully created!');
//
//			$nextAction = $form->get('saveAndExit')->isClicked()
//			        ? $this->generateUrl('booking_index_schedule', array('date' => $booking->getFlightdate()->format('Y-m-d')))
//			        : $this->generateUrl('booking_quick');
//	        return $this->redirect($nextAction);
//	    }
//		return $this->render('AazpBookingBundle:Booking:quick.html.twig', array('form' => $form->createView()));
//    }
//
//    public function updateAction($id)
//    {
//    	$request = $this->get('request');
//    	$flightScheduleTimeRepos = $this->getDoctrine()->getRepository('AazpBookingBundle:FlightScheduleTime');
//    	$productRepository = $this->getDoctrine()->getRepository('AazpBookingBundle:Product');
//    	$meetingLocationRepository = $this->getDoctrine()->getRepository('AazpBookingBundle:MeetingLocation');
//
//        $em = $this->getDoctrine()->getManager();
//        $entity = $em->getRepository('AazpBookingBundle:Booking')->find($id);
//
//        if (!$entity) {
//            throw $this->createNotFoundException('Unable to find Booking entity.');
//        }
//
//	    $originalPassengers = new ArrayCollection();
//
//	    // Create an ArrayCollection of the current Passenger objects in the database
//	    foreach ($entity->getPassengers() as $passenger)
//	    {
//	        $originalPassengers->add($passenger);
//	    }
//
//	    $flightScheduleTimes = $flightScheduleTimeRepos->getFlightScheduleTimesFor($entity->getFlightDate());
//	    $preferredFlights = $productRepository->getPreferredFlightProducts();
//	    $preferredMeetingLocations = $meetingLocationRepository->getPreferredMeetingLocations();
//
//	    if($entity->getMeetingTime() === null || $entity->getMeetingTime()->format('H:i:s') == '00:00:00')
//	    {
//	        $meetingTime = clone $entity->getFlightdate();
//// 	        $meetingTime = \DateTime::createFromFormat('d-m-Y', $entity->getFlightdate()->format('H:i'));
//	        $entity->setMeetingTime($meetingTime);
//
//	        foreach ($flightScheduleTimes as $flightScheduleTime)
//	        {
//	            if($entity->getMeetingTime()->format('H:i') >= $flightScheduleTime->getScheduleStartTime()->format('H:i') &&
//	                $entity->getMeetingTime()->format('H:i') <= $flightScheduleTime->getScheduleEndTime()->format('H:i') &&
//	                $entity->getFlightScheduleTime() === null )
//	            {
//	                $entity->setFlightScheduleTime($flightScheduleTime);
//	            }
//	        }
//	    }
//
//
//	    $form = $this->createForm(new BookingType($flightScheduleTimes, $preferredFlights, $preferredMeetingLocations), $entity);
//		$form->add('saveAndExit', 'submit', array('label' => 'Save'));
//		$form->add('saveAndConfirm', 'submit', array('label' => 'Confirm'));
// 		$form->add('cancel', 'submit', array('attr' => array('formnovalidate' => true, 'data-toggle' => 'modal', 'data-target' => '#cancelWarning', )));
//
//		$form->handleRequest($request);
//
//		if($form->get('cancel')->isClicked())
//		{
//			return $this->redirect($this->generateUrl('booking_index_schedule', array('date' => $entity->getFlightdate()->format('Y-m-d'))));
//		}
//
//        if ($form->isValid()) {
//	        // remove the relationship between the Passenger and the Booking
//	        foreach ($originalPassengers as $passenger) {
//	            if (false === $entity->getPassengers()->contains($passenger)) {
//	                $em->remove($passenger);
//	            }
//	        }
//			foreach($entity->getPassengers() as $passenger)
//			{
//				if($passenger->getFlight() == null)
//				{
//					$passenger->setFlight($entity->getFlight());
//				}
//			}
//			if($form->get('saveAndConfirm')->isClicked())
//			{
//				$entity->setStatus(Booking::STATUS_CONFIRMED);
//			}
//			$entity->setLastUpdatedBy($this->getUser());
//
//            $em->persist($entity);
//            $em->flush();
//
//			$this->get('session')->getFlashBag()->add('success', 'Booking has been successfully updated!');
//			$this->get('session')->set('current_date', $entity->getFlightDate()->format('Y-m-d'));
//
//			$nextAction = ($form->get('saveAndExit')->isClicked() or $form->get('saveAndConfirm')->isClicked())
//			        ? $this->generateUrl('booking_show', array('id'=> $entity->getId()))
//			        : $this->generateUrl('booking_update', array ('id'=> $id));
//	        return $this->redirect($nextAction);
//        }
//		return $this->render('AazpBookingBundle:Booking:edit.html.twig', array(
//            'entity'      => $entity,
//            'form'   => $form->createView(),
//        ));
//
//     }
//
    /**
     * @Route("/confirm/{id}", name="booking_confirm")
     */
     public function confirmAction($id)
     {
         $em = $this->getDoctrine()->getManager();
         $booking = $em->getRepository('App\Entity\Booking')->find($id);

         if (!$booking) {
             throw $this->createNotFoundException('Unable to find Booking entity.');
         }

         $booking->setLastUpdatedBy($this->getUser());
         $booking->setStatus(Booking::STATUS_CONFIRMED);

         $em->flush();

         $this->get('session')->getFlashBag()->add('success', 'Booking has been successfully confirmed!');

         return $this->redirect($this->generateUrl('booking_schedule_view_list'));
     }

    /**
     * @Route("/unconfirm/{id}", name="booking_unconfirm")
     */
     public function unconfirmAction($id)
     {
         $em = $this->getDoctrine()->getManager();
         $booking = $em->getRepository('App\Entity\Booking')->find($id);

         if (!$booking) {
             throw $this->createNotFoundException('Unable to find Booking entity.');
         }

         $booking->setLastUpdatedBy($this->getUser());
         $booking->setStatus(Booking::STATUS_NEW);

         $em->flush();

         $this->get('session')->getFlashBag()->add('success', 'Booking has been successfully unconfirmed!');

         return $this->redirect($this->generateUrl('booking_schedule_view_list'));
     }

    /**
     * @Route("/delete/{id}", name="booking_schedule_delete")
     */
      public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('App\Entity\Booking')->find($id);

	    if (!$entity) {
	        throw $this->createNotFoundException('Unable to find Booking entity.');
        }

		$entity->setLastUpdatedBy($this->getUser());

        $em->remove($entity);
        $em->flush();

 		$this->get('session')->getFlashBag()->add('success', 'Booking has been successfully deleted!');

        return $this->redirect($this->generateUrl('booking_schedule_view_list'));
    }

    /**
     * @Route("/payment/{id}", name="booking_schedule_payment")
     */
    public function bookingPaymentAction($id)
    {
        $request = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $paymentsRepos = $em->getRepository(Payment::class);

        $booking = $em->getRepository(Booking::class)->find($id);
        if (!$booking)
        {
            throw $this->createNotFoundException('Unable to find Booking entity.');
        }

        //PRELOAD FORM
        foreach ($booking->getPassengers() as $passenger)
        {
            if($passenger->getPurchase() === null)
            {
                $purchase = new Purchase();

                $product_category_photo = $em->getRepository(ProductCategory::class)->findOneByName('FLIGHT-PHOTO');
                $product_photo_option = $em->getRepository(Product::class)->findOneByProductCategory($product_category_photo);
                if (!$product_photo_option) {
                    throw $this->createNotFoundException('Unable to find Flight Photo Product entity.');
                }

                $purchase_item_flight = new PurchaseItem($passenger->getFlight());
                $purchase_item_photo_option = new PurchaseItem($product_photo_option);

                $purchase->addPurchaseItem($purchase_item_flight);
                $purchase->addPurchaseItem($purchase_item_photo_option);

                $passenger->setPurchase($purchase);
            }
        }

        //LOAD PAYMENT LIST
        $payments = $paymentsRepos->getPaymentsForBooking($booking);
        $paymentViewHelper = new PaymentViewHelper();
        $paymentViewList = $paymentViewHelper->processPayments($payments);

        $form = $this->createFormBuilder($booking)
//            ->add('passengers', CollectionType::class, array('type' => new BPPassengerType(), 'allow_add' => false, 'allow_delete' => false, 'by_reference' => false))
            ->add('passengers', CollectionType::class, array('entry_type' => BPPassengerType::class, 'allow_add' => false, 'allow_delete' => false, 'by_reference' => false))
//            ->add('paymentType', EntityType::class, array('label' => 'Payment Type', 'class' => PaymentType::class,'property' => 'name', 'mapped' => false, 'expanded' => true))
            ->add('paymentType', EntityType::class, array('label' => 'Payment Type', 'class' => PaymentType::class, 'choice_label' => 'name', 'mapped' => false, 'expanded' => true))
//            ->add('paymentAmount', NumberType::class, array('precision' => 2, 'data' => $booking->calculateBalance(), 'mapped' => false))
            ->add('paymentAmount', NumberType::class, array('scale' => 2, 'data' => $booking->calculateBalance(), 'mapped' => false))
            ->add('sumupRef', TextType::class, array('mapped' => false, 'required' => false))
            ->add('description', TextType::class, array('mapped' => false, 'required' => false))
            ->add('pay', SubmitType::class)
            ->add('cancel', SubmitType::class, array('attr' => array('formnovalidate' => true, 'data-toggle' => 'modal', 'data-target' => '#cancelWarning', )))
            ->getForm();

        $form->handleRequest($request);

        if($request->isXmlHttpRequest() )
        {
            $paymentType = $form->get('paymentType')->getData();

            $product_category_card_fee = $em->getRepository(ProductCategory::class)->findOneByName('CARD-FEE');

            $product_card_fee = $em->getRepository(Product::class)->findOneByProductCategory($product_category_card_fee);
            if (!$product_card_fee)
            {
                throw $this->createNotFoundException('Unable to find Card Fee Product entity.');
            }

            //IF CASH OR INVOICE OR VOUCHER THEN REMOVE CARD FEE Purchase Item IF IT EXISTS
            if($paymentType->getName() === "CASH" ||
                $paymentType->getName() === "INVOICE" ||
                $paymentType->getName() === "VOUCHER")
            {
                foreach ($booking->getPassengers() as $passenger)
                {
                    //check if CARD FEE EXISTS
                    foreach ($passenger->getPurchase()->getPurchaseItems() as $purchaseItem)
                    {
                        //Remove if it does
                        if($purchaseItem->getProduct() == $product_card_fee)
                        {
                            //CAN ONLY REMOVE IF NO OTHER PAYMENT HAS BEEN MADE WITH A CARD FEE **********************************
                            if(!$passenger->getPurchase()->hasNoPriorCardPayment())
                            {
                                $passenger->getPurchase()->removePurchaseItem($purchaseItem);
                            }
                        }
                    }
                }
            }
            else if($paymentType->getName() === "VISA" ||
                $paymentType->getName() === "M/C" ||
                $paymentType->getName() === "JCB" ||
                $paymentType->getName() === "AMEX" ||
                $paymentType->getName() === "Maestro")
            {
                //check if CARD FEE EXISTS
                //Add if it doesn't
                // $noOfFlights = 0;
                $cardFeeExists = false;
                foreach ($booking->getPassengers() as $passenger)
                {
                    foreach ($passenger->getPurchase()->getPurchaseItems() as $purchaseItem)
                    {
                        if($purchaseItem->getProduct() == $product_card_fee)
                        {
                            // $purchase->removePurchaseItem($purchaseItem);
                            //IF CARD THEN ADD CARD FEE Purchase Item AND CALCULATE ITS AMOUNT (FLIGHTS x FEE)
                            // $purchaseItem->setAmount($paymentType->getFee());
                            $cardFeeExists = true;
                        }
                        //Count the number of Flight Products in order to calculate the Card Fee.
                        // if($purchaseItem->getProduct()->getProductCategory()->getDescription() == "FLIGHT")
                        // {
                        // $noOfFlights++;
                        // }
                    }
                    if(!$cardFeeExists)
                    {
                        //Add Purchase Items to the Purchase
                        $purchase_item_card_fee = new PurchaseItem($product_card_fee);
                        $passenger->getPurchase()->addPurchaseItem($purchase_item_card_fee);
                        $purchase_item_card_fee->setAmount($product_card_fee->getPrice());
                    }
                }
            }

            $passenger->setPurchase($passenger->getPurchase());

            $form = $this->createForm(new PurchaseType($em), $passenger->getPurchase());

            $form = $this->createFormBuilder($booking)
                ->add('passengers', CollectionType::class, array('entry_type' => BPPassengerType::class, 'allow_add' => false, 'allow_delete' => false, 'by_reference' => false))
                ->add('paymentType', EntityType::class, array('label' => 'Payment Type', 'class' => PaymentType::class, 'choice_label' => 'name', 'mapped' => false, 'expanded' => true))
                ->add('paymentAmount', NumberType::class, array('scale' => 2, 'data' => $booking->calculateBalance(), 'mapped' => false))
                ->add('sumupRef', TextType::class, array('mapped' => false, 'required' => false))
                ->add('description', TextType::class, array('mapped' => false, 'required' => false))
                ->add('pay', SubmitType::class)
                ->add('cancel', SubmitType::class, array('attr' => array('formnovalidate' => true, 'data-toggle' => 'modal', 'data-target' => '#cancelWarning', )))
                ->getForm();

            $template = $this->renderView('booking/payment/booking.payment.summary.form.html.twig', array(
                'payments' => $payments,
                'paymentViewList' => $paymentViewList,
                'booking' => $booking,
                'form'	=> $form->createView(),
            ));

            $json = json_encode($template);
            $response = new Response($json, 200);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        if ($form->isSubmitted() && $form->isValid())
        {
            $paymentAmount = $form->get('paymentAmount')->getData();
            $paymentType = $form->get('paymentType')->getData();
            $paymentDescription = $form->get('description')->getData();
            $paymentSumupRef = $form->get('sumupRef')->getData();
            $transactionNo = date('YmdHis');

            $passengers = $booking->getPassengers();

            $bookingPaymentHelper = new BookingPaymentHelper($paymentAmount, $passengers);
            if($bookingPaymentHelper->getOwingTotal() == 0.0)
            {
                $paymentMessage = 'BOOKING ALREADY PAID IN FULL. Payment '.$paymentAmount.' CHF not accepted!';
                $messageType = 'danger';
            }
            else if($bookingPaymentHelper->hasEvenPaymentDistribution())
            {
                $passengerAmount = $bookingPaymentHelper->getPassengerPaymentAmount();
                foreach ($passengers as $passenger)
                {
                    $thisPassengerPaymentAmount = $bookingPaymentHelper->getPassengerPaymentStatus()[$passenger->getId()];
                    $passengerPayment = $thisPassengerPaymentAmount->getSubPaymentTotal();
                    $passengerOwingBalance = $thisPassengerPaymentAmount->getOwingBalance();
                    if($passengerOwingBalance <> 0.0)
                    {
                        $payment = new Payment();
                        $payment->setAmount($paymentAmount);
                        $payment->setSubAmount($passengerAmount);
                        $payment->setPaymentType($paymentType);
                        $payment->setDescription($paymentDescription);
                        $payment->setSumupRef($paymentSumupRef);
                        $payment->setTransactionNo($transactionNo);

                        $purchase = $passenger->getPurchase();
                        if($purchase === null)
                        {
                            $purchase = new Purchase();
                        }
                        $payment->addPurchase($purchase);
                        $purchase->addPayment($payment);
                    }
                }
                $paymentMessage = 'Payment '.number_format($paymentAmount,2).' CHF successful!';
                $messageType = 'success';
            }
            else
            {
                if(number_format($paymentAmount,2) == number_format($bookingPaymentHelper->getOwingTotal(),2))
                {
                    foreach ($passengers as $passenger)
                    {
                        $payment = new Payment();
                        $payment->setAmount($paymentAmount);
                        $payment->setSubAmount($passenger->calculateOwing());
                        $payment->setPaymentType($paymentType);
                        $payment->setDescription($paymentDescription);
                        $payment->setSumupRef($paymentSumupRef);
                        $payment->setTransactionNo($transactionNo);

                        $purchase = $passenger->getPurchase();
                        if($purchase === null)
                        {
                            $purchase = new Purchase();
                        }
                        $payment->addPurchase($purchase);
                        $purchase->addPayment($payment);
                    }
                }
                else
                {
                   $paymentAmountBalance = $paymentAmount;
                   while($paymentAmountBalance <> 0.0)
                   {
                       $calculatedPaymentAmount = $bookingPaymentHelper->process($paymentAmountBalance);
                       if($calculatedPaymentAmount == 0.0)
                       {
                           $paymentAmountBalance = 0.0;
                       }
                       $paymentAmountBalance = $paymentAmountBalance - $calculatedPaymentAmount;
                   }
                   foreach ($passengers as $passenger)
                   {
                       $thisPassengerPaymentAmount = $bookingPaymentHelper->getPassengerPaymentStatus()[$passenger->getId()];
                       $passengerPayment = $thisPassengerPaymentAmount->getSubPaymentTotal();
                       $passengerOwingBalance = $thisPassengerPaymentAmount->getOwingBalance();
                       if($passengerPayment > 0.0)
                       {
                           $payment = new Payment();
                           $payment->setAmount($paymentAmount);
                           $payment->setSubAmount($passengerPayment);
                           $payment->setPaymentType($paymentType);
                           $payment->setDescription($paymentDescription);
                           $payment->setSumupRef($paymentSumupRef);
                           $payment->setTransactionNo($transactionNo);

                           $purchase = $passenger->getPurchase();
                           if($purchase === null)
                           {
                               $purchase = new Purchase();
                           }
                           $payment->addPurchase($purchase);
                           $purchase->addPayment($payment);
                       }
                   }
                }
                $paymentMessage = 'Payment '.number_format($paymentAmount,2).' CHF successful!';
                $messageType = 'success';
            }

            if($booking->paidInFull())
            {
                $booking->setStatus(Booking::STATUS_PAYMENT_FULL);
            }
            else
            {
                $booking->setStatus(Booking::STATUS_PAYMENT_PART);
            }

            $em->persist($booking);
            $em->flush();
            $this->get('session')->getFlashBag()->add($messageType, $paymentMessage);
            return $this->redirect($this->generateUrl('booking_schedule_show', array ('id'=> $booking->getId())));
        }
        return $this->render('booking/booking.payment.summary.html.twig', array(
            'paymentViewList' => $paymentViewList,
            'payments' => $payments,
            'booking' => $booking,
            'form'	=> $form->createView(),
        ));

    }

//    public function oldBookingPaymentAction($id)
//    {
//    	$request = Request::createFromGlobals();
//        $em = $this->getDoctrine()->getManager();
//
//        $booking = $em->getRepository('AazpBookingBundle:Booking')->find($id);
//		if (!$booking) {
//            throw $this->createNotFoundException('Unable to find Booking entity.');
//        }
//
////PRELOAD FORM
//		foreach ($booking->getPassengers() as $passenger) {
//
//			if($passenger->getPurchase() === null)
//			{
//				$purchase = new Purchase();
//
//				$product_category_photo = $em->getRepository('AazpBookingBundle:ProductCategory')->findOneByName('FLIGHT-PHOTO');
//				$product_photo_option = $em->getRepository('AazpBookingBundle:Product')->findOneByProductCategory($product_category_photo);
//			    if (!$product_photo_option) {
//			        throw $this->createNotFoundException('Unable to find Flight Photo Product entity.');
//		        }
//
//				$purchase_item_flight = new PurchaseItem($passenger->getFlight());
//				$purchase_item_photo_option = new PurchaseItem($product_photo_option);
//
//				$purchase->addPurchaseItem($purchase_item_flight);
//				$purchase->addPurchaseItem($purchase_item_photo_option);
//
//				$passenger->setPurchase($purchase);
//			}
//		}
//
////LOAD PAYMENT LIST
//		$payments = new ArrayCollection();
//		foreach ($booking->getPassengers() as $passenger) {
//			foreach($passenger->getPurchase()->getPayments() as $payment) {
//				if(!$payments->contains($payment))
//				{
//					$payments[] = $payment;
//				}
//			}
//		}
//
//		$form = $this->createFormBuilder($booking)
//			->add('passengers', 'collection', array('type' => new BPPassengerType(), 'allow_add' => false, 'allow_delete' => false, 'by_reference' => false))
//			->add('paymentType', 'entity', array('label' => 'Payment Type', 'class' => 'AazpBookingBundle:PaymentType','property' => 'name', 'mapped' => false, 'expanded' => true))
//			->add('paymentAmount', 'number', array('precision' => 2, 'data' => $booking->calculateBalance(), 'mapped' => false))
//			->add('sumupRef', 'text', array('mapped' => false, 'required' => false))
//			->add('description', 'text', array('mapped' => false, 'required' => false))
//			->add('pay', 'submit')
//	 		->add('cancel', 'submit', array('attr' => array('formnovalidate' => true, 'data-toggle' => 'modal', 'data-target' => '#cancelWarning', )))
//			->getForm();
//
//		$form->handleRequest($request);
//
//			if($request->isXmlHttpRequest() )
//			{
//				$paymentType = $form->get('paymentType')->getData();
//
//				$product_category_card_fee = $em->getRepository('AazpBookingBundle:ProductCategory')->findOneByName('CARD-FEE');
//
//				$product_card_fee = $em->getRepository('AazpBookingBundle:Product')->findOneByProductCategory($product_category_card_fee);
//				if (!$product_card_fee)
//				{
//			        throw $this->createNotFoundException('Unable to find Card Fee Product entity.');
//		        }
//
//				//IF CASH OR INVOICE OR VOUCHER THEN REMOVE CARD FEE Purchase Item IF IT EXISTS
//				if($paymentType->getName() === "CASH" ||
//					$paymentType->getName() === "INVOICE" ||
//					$paymentType->getName() === "VOUCHER")
//				{
//					foreach ($booking->getPassengers() as $passenger)
//					{
//						//check if CARD FEE EXISTS
//						foreach ($passenger->getPurchase()->getPurchaseItems() as $purchaseItem)
//						{
//							//Remove if it does
//							if($purchaseItem->getProduct() == $product_card_fee)
//							{
//								//CAN ONLY REMOVE IF NO OTHER PAYMENT HAS BEEN MADE WITH A CARD FEE **********************************
//								if(!$passenger->getPurchase()->hasNoPriorCardPayment())
//								{
//									$passenger->getPurchase()->removePurchaseItem($purchaseItem);
//								}
//							}
//						}
//					}
//				}
//				else if($paymentType->getName() === "VISA" ||
//						$paymentType->getName() === "M/C" ||
//						$paymentType->getName() === "JCB" ||
//						$paymentType->getName() === "AMEX" ||
//						$paymentType->getName() === "Maestro")
//				{
//					//check if CARD FEE EXISTS
//					//Add if it doesn't
//					// $noOfFlights = 0;
//					$cardFeeExists = false;
//					foreach ($booking->getPassengers() as $passenger)
//					{
//						foreach ($passenger->getPurchase()->getPurchaseItems() as $purchaseItem)
//						{
//							if($purchaseItem->getProduct() == $product_card_fee)
//							{
//								// $purchase->removePurchaseItem($purchaseItem);
//								//IF CARD THEN ADD CARD FEE Purchase Item AND CALCULATE ITS AMOUNT (FLIGHTS x FEE)
//								// $purchaseItem->setAmount($paymentType->getFee());
//								$cardFeeExists = true;
//							}
//							//Count the number of Flight Products in order to calculate the Card Fee.
//							// if($purchaseItem->getProduct()->getProductCategory()->getDescription() == "FLIGHT")
//							// {
//								// $noOfFlights++;
//							// }
//						}
//						if(!$cardFeeExists)
//						{
//							//Add Purchase Items to the Purchase
//							$purchase_item_card_fee = new PurchaseItem($product_card_fee);
//							$passenger->getPurchase()->addPurchaseItem($purchase_item_card_fee);
//							$purchase_item_card_fee->setAmount($product_card_fee->getPrice());
//						}
//					}
//				}
//
//				$passenger->setPurchase($passenger->getPurchase());
//
//				$form = $this->createForm(new PurchaseType($em), $passenger->getPurchase());
//
//				$form = $this->createFormBuilder($booking)
//					->add('passengers', 'collection', array('type' => new BPPassengerType(), 'allow_add' => false, 'allow_delete' => false, 'by_reference' => false))
//					->add('paymentType', 'entity', array('label' => 'Payment Type', 'class' => 'AazpBookingBundle:PaymentType','property' => 'name', 'data' => $paymentType, 'mapped' => false, 'expanded' => true))
//					->add('paymentAmount', 'number', array('precision' => 2, 'data' => $booking->calculateBalance(), 'mapped' => false))
//					->add('description', 'text', array('mapped' => false, 'required' => false))
//					->add('pay', 'submit')
//			 		->add('cancel', 'submit', array('attr' => array('formnovalidate' => true, 'data-toggle' => 'modal', 'data-target' => '#cancelWarning', )))
//					->getForm();
//
//
//				$template = $this->renderView('AazpBookingBundle:Purchase:booking.payment.summary.form.html.twig', array(
//					'payments' => $payments,
//		            'booking' => $booking,
//		            'form'	=> $form->createView(),
//			    ));
//
//			    $json = json_encode($template);
//			    $response = new Response($json, 200);
//			    $response->headers->set('Content-Type', 'application/json');
//			    return $response;
//			}
//
//		if ($form->isValid())
//		{
//			$paymentAmount = $form->get('paymentAmount')->getData();
//			$noOfPassengers = sizeof($booking->getPassengers());
//			$payment = new Payment();
//			foreach($booking->getPassengers() as $passenger)
//			{
//				$purchase = $passenger->getPurchase();
//				if($purchase === null)
//				{
//					$purchase = new Purchase();
//				}
//				$payment->addPurchase($purchase);
//				$purchase->addPayment($payment);
//			}
//
//			$payment->setAmount($paymentAmount);
//			$payment->setPaymentType($form->get('paymentType')->getData());
//			$payment->setDescription($form->get('description')->getData());
//			$payment->setSumupRef($form->get('sumupRef')->getData());
//
//			if($booking->paidInFull())
//			{
//				$booking->setStatus(Booking::STATUS_PAYMENT_FULL);
//			}
//			else
//			{
//				$booking->setStatus(Booking::STATUS_PAYMENT_PART);
//			}
//
//			$em->persist($booking);
//			$em->flush();
//
//			$this->get('session')->getFlashBag()->add('success', 'Payment '.$paymentAmount.' CHF has been successful!');
//
//			return $this->redirect($this->generateUrl('booking_show', array ('id'=> $booking->getId())));
//	        return $this->redirect($this->generateUrl('booking_index_schedule'));
//		}
//
//
//		return $this->render('AazpBookingBundle:Purchase:booking.payment.summary.html.twig', array(
//			'payments' => $payments,
//            'booking' => $booking,
//            'form'	=> $form->createView(),
//			));
//	}
//
//    public function bookingPaymentAction_ORIG($id)
//    {
//		$request = Request::createFromGlobals();
//        $em = $this->getDoctrine()->getManager();
//		$purchaseBalance = 0;
//		$purchasesTotal = 0;
//
//        $booking = $em->getRepository('AazpBookingBundle:Booking')->find($id);
//		if (!$booking) {
//            throw $this->createNotFoundException('Unable to find Booking entity.');
//        }
//
//		$payments = new ArrayCollection();
//		foreach($booking->getPassengers() as $passenger)
//		{
//			if($passenger->getPurchase() != null)
//			{
//				foreach($passenger->getPurchase()->getPayments() as $payment)
//				{
//					if(!$payments->contains($payment))
//					{
//						$payments[] = $payment;
//					}
//				}
//			}
//		}
//
//		$purchases = new ArrayCollection();
//		//create purchase for each passenger
//		foreach ( $booking->getPassengers() as $passenger)
//		{
//			if($passenger->getPurchase() != null) //purchase already exists
//			{
//				$purchase = $passenger->getPurchase();
//			}
//			else
//			{
//				$purchase = new Purchase();
//
//				$product_flight = $em->getRepository('AazpBookingBundle:Product')->find($passenger->getFlight()->getId());
//			    if (!$product_flight) {
//			        throw $this->createNotFoundException('Unable to find Flight Product entity.');
//		        }
//
//				$product_category_photo = $em->getRepository('AazpBookingBundle:ProductCategory')->findOneByName('FLIGHT-PHOTO');
//				$product_photo = $em->getRepository('AazpBookingBundle:Product')->findOneByProductCategory($product_category_photo->getId());
//			    if (!$product_photo) {
//			        throw $this->createNotFoundException('Unable to find Flight Photo Product entity.');
//		        }
//
//				$flight_purchase_item = new PurchaseItem($product_flight);
//				$photo_purchase_item = new PurchaseItem($product_photo);
//
//				$purchase->addPurchaseItem($flight_purchase_item);
//				$purchase->addPurchaseItem($photo_purchase_item);
//				$passenger->setPurchase($purchase);
//			}
//			$purchaseBalance += $purchase->calculateBalance();
//			$purchasesTotal += $purchase->calculatePurchaseTotal();
//			$purchases[] = $purchase;
//		}
//		$defaults = array('purchases' => $purchases);
//
//		$form = $this->createFormBuilder($defaults)
//			->add('purchases', 'collection', array('type' => new PurchaseType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false))
//			->add('paymentType', 'entity', array('label' => 'Payment Type', 'class' => 'AazpBookingBundle:PaymentType','property' => 'name', 'mapped' => false, 'expanded' => true))
//			->add('paymentAmount', 'number', array('precision' => 2, 'data' => $purchaseBalance, 'mapped' => false))
//			->add('description', 'text', array('mapped' => false, 'required' => false))
//			->add('confirm', 'submit')
//	 		->add('cancel', 'submit', array('attr' => array('formnovalidate' => true, 'data-toggle' => 'modal', 'data-target' => '#cancelWarning', )))
//			->getForm();
//
//		$form->handleRequest($request);
//
//		if ($form->isValid())
//		{
//			$paymentAmount = $form->get('paymentAmount')->getData();
//			$payment = new Payment();
//			foreach($purchases as $purchase)
//			{
//				$purchaseTotal = $purchase->calculatePurchaseTotal();
//				$paymentFactor = $purchaseTotal / $purchasesTotal;
//				$purchasePaymentAmount = $paymentFactor * $paymentAmount;
//				$purchase->addPayment($payment);
//				$payment->addPurchase($purchase);
//			}
//			$payment->setPaymentType($form->get('paymentType')->getData());
//			$payment->setDescription($form->get('description')->getData());
//			$payment->setAmount($paymentAmount);
//
//			$booking->setStatus(Booking::STATUS_CONFIRMED);
//
//			$em->persist($payment);
//			$em->flush();
//
//			$this->get('session')->getFlashBag()->add('success', 'Payment '.$payment->getAmount().' CHF has been successful!');
//
//			return $this->redirect($this->generateUrl('booking_show', array ('id'=> $booking->getId())));
//	        return $this->redirect($this->generateUrl('booking_index_schedule'));
//		}
//
//		return $this->render('AazpBookingBundle:Purchase:booking.summary.html.twig', array(
//            'purchaseBalance' => $purchaseBalance,
//            'booking' => $booking,
//            'form'	=> $form->createView(),
//            'payments' => $payments,
//			));
//	}
//
    /**
     * @Route("/passenger/payment/{passenger_id}", name="booking_schedule_passenger_payment")
     */
	public function passengerPaymentAction($passenger_id)
    {
	    $request = Request::createFromGlobals();
	    $em = $this->getDoctrine()->getManager();
	    $passengerRepos = $em->getRepository(Passenger::class);
	    $productCategoryRepos = $em->getRepository(ProductCategory::class);
	    $productRepos = $em->getRepository(Product::class);
	    $paymentsRepos = $em->getRepository(Payment::class);

		//Query the selected Passenger
		$passenger = $request->attributes->get('passenger', $passengerRepos->find($passenger_id));
	    if (!$passenger)
	    {
	        throw $this->createNotFoundException('Unable to find Passenger entity.');
        }

		$purchase = $passenger->getPurchase();
		//Has this Passenger made a Payment. If yes then Purchase exists.
		if($purchase === NULL) //If Purchase does not exist then preload the form with default products.
		{
			$purchase = new Purchase();

			$product_category_photo = $productCategoryRepos->findOneByName('FLIGHT-PHOTO');

			$product_photo_option = $productRepos->findOneByProductCategory($product_category_photo);
		    if (!$product_photo_option)
		    {
		        throw $this->createNotFoundException('Unable to find Flight Photo Product entity.');
	        }

			$purchase_item_flight = new PurchaseItem($passenger->getFlight());
			$purchase_item_photo_option = new PurchaseItem($product_photo_option);

			//Add Purchase Items to the Purchase
			$purchase->addPurchaseItem($purchase_item_flight);
			$purchase->addPurchaseItem($purchase_item_photo_option);

			//Set the Purchase on the Passenger
			$passenger->setPurchase($purchase);
		}
		$purchase->setPaymentAmount($passenger->calculateOwing());

		$form = $this->createForm(PurchaseType::class, $purchase);

		$form->handleRequest($request);

			//Ajax call triggered by onChange event on PaymentType radio button in form
			if($request->isXmlHttpRequest() )
			{
				$paymentType = $form->get('paymentType')->getData();

				$product_category_card_fee = $productCategoryRepos->findOneByName('CARD-FEE');

				$product_card_fee = $productRepos->findOneByProductCategory($product_category_card_fee);
				if (!$product_card_fee)
				{
			        throw $this->createNotFoundException('Unable to find Card Fee Product entity.');
		        }

				if($paymentType->isCardPayment())
				{
					//check if CARD FEE EXISTS
					//Add if it doesn't
					// $noOfFlights = 0;
					$cardFeeExists = false;
					foreach ($purchase->getPurchaseItems() as $purchaseItem)
					{
						if($purchaseItem->getProduct() == $product_card_fee)
						{
							// $purchase->removePurchaseItem($purchaseItem);
							//IF CARD THEN ADD CARD FEE Purchase Item AND CALCULATE ITS AMOUNT (FLIGHTS x FEE)
							// $purchaseItem->setAmount($paymentType->getFee());
							$cardFeeExists = true;
						}
						//Count the number of Flight Products in order to calculate the Card Fee.
						// if($purchaseItem->getProduct()->getProductCategory()->getDescription() == "FLIGHT")
						// {
							// $noOfFlights++;
						// }
					}
					if(!$cardFeeExists)
					{
						//Add Purchase Items to the Purchase
						$purchase_item_card_fee = new PurchaseItem($product_card_fee);
						$purchase->addPurchaseItem($purchase_item_card_fee);
						$purchase_item_card_fee->setAmount($product_card_fee->getPrice());
					}
				}
				//IF CASH OR INVOICE OR VOUCHER THEN REMOVE CARD FEE Purchase Item IF IT EXISTS
				else
				{
				    //check if CARD FEE EXISTS
				    foreach ($purchase->getPurchaseItems() as $purchaseItem)
				    {
				        //Remove if it does
				        if($purchaseItem->getProduct() == $product_card_fee)
				        {
				            //CAN ONLY REMOVE IF NO OTHER PAYMENT HAS BEEN MADE WITH A CARD FEE **********************************
				            if(!$purchase->hasNoPriorCardPayment())
				            {
				                $purchase->removePurchaseItem($purchaseItem);
				            }
				        }
				    }
				}

				$passenger->setPurchase($purchase);

				$form = $this->createForm(PurchaseType::class, $purchase);
//                 if($paymentType->isCardPayment())
//                 {
//                     $form->remove('cardDigits');
//                     $form->remove('sumupAccount');

//                     $form->add('cardDigits', 'hidden', array('mapped' => false, 'required' => false));
//                     $form->add('sumupAccount', 'hidden', array('label' => false, 'mapped' => false, 'required' => false));
//                 } else {
//                     $form->remove('cardDigits');
//                     $form->remove('sumupAccount');
//                     $form->add('cardDigits', 'integer', array('mapped' => false, 'required' => false));
//                     $form->add('sumupAccount', 'entity', array('mapped' => false, 'required' => false, 'label' => 'SumUp ACCOUNT', 'class' => 'AazpBookingBundle:Account','property' => 'name', 'expanded' => false, ));
//                 }
				$template = $this->renderView('booking/payment/passenger.payment.summary.form.html.twig', array(
		            'passenger' => $passenger,
		            'form'	=> $form->createView(),
			    ));

			    $json = json_encode($template);
			    $response = new Response($json, 200);
			    $response->headers->set('Content-Type', 'application/json');
			    return $response;
			}

		//If form is Valid create Payment and persist.
		if ($form->isSubmitted() && $form->isValid())
		{
		    $transactionNo = date('YmdHis');
			$payment = new Payment();
			$payment->setAmount($form->get('paymentAmount')->getData());
// 			$payment->setSubAmount($form->get('paymentAmount')->getData());
			$payment->setPaymentType($form->get('paymentType')->getData());
			$payment->setSumUpRef($form->get('sumupRef')->getData());
			$payment->setDescription($form->get('description')->getData());
			$payment->setTransactionNo($transactionNo);

			$passenger->getPurchase()->addPayment($payment);
			if($passenger->getBooking()->paidInFull())
			{
				$passenger->getBooking()->setStatus(Booking::STATUS_PAYMENT_FULL);
			}
			else
			{
				$passenger->getBooking()->setStatus(Booking::STATUS_PAYMENT_PART);
			}

			$em->persist($passenger->getPurchase());
			$em->flush();
			$this->get('session')->getFlashBag()->add('success', 'Payment '.number_format($payment->getAmount(),2).' CHF has been successful!');

			return $this->redirect($this->generateUrl('booking_schedule_show', array ('id'=> $passenger->getBooking()->getId())));
		}

		$payments = $paymentsRepos->getPaymentsForPassenger($passenger);
// 		throw new \Exception("Her ".sizeof($payments));
		$paymentViewHelper = new PaymentViewHelper();
		$paymentViewList = $paymentViewHelper->processPassengerPayments($payments);
		return $this->render('booking/passenger.payment.summary.html.twig', array(
            'passenger' => $passenger,
		    'paymentViewList' => $paymentViewList,
            'form'	=> $form->createView(),
			));
	}

//    public function passengerPaymentAction_ORIG($passenger_id)
//    {
//    	$request = Request::createFromGlobals();
//
//        $em = $this->getDoctrine()->getManager();
//		$passenger = $request->attributes->get('passenger', $em->getRepository('AazpBookingBundle:Passenger')->find($passenger_id));
//	    if (!$passenger) {
//	        throw $this->createNotFoundException('Unable to find Passenger entity.');
//        }
//
//		if($passenger->getPurchase() == null)
//		{
//			//no payment made
//			$product_flight = $em->getRepository('AazpBookingBundle:Product')->find($passenger->getFlight()->getId());
//		    if (!$product_flight) {
//		        throw $this->createNotFoundException('Unable to find Flight Product entity.');
//	        }
//
//			$product_category_photo = $em->getRepository('AazpBookingBundle:ProductCategory')->findOneByName('FLIGHT-PHOTO');
//			$product_photo = $em->getRepository('AazpBookingBundle:Product')->findOneByProductCategory($product_category_photo->getId());
//		    if (!$product_photo) {
//		        throw $this->createNotFoundException('Unable to find Flight Photo Product entity.');
//	        }
//
//			$purchase = new Purchase();
//
//			$flight_purchase_item = new PurchaseItem($product_flight);
//			$photo_purchase_item = new PurchaseItem($product_photo);
//
//			$purchase->addPurchaseItem($flight_purchase_item);
//			$purchase->addPurchaseItem($photo_purchase_item);
//
//			$passenger->setPurchase($purchase);
//		}
//
//		//Calculate total Payment
//		//for each Payment of this purchase
//		$purchasePaymentTotal = 0.0;
//		foreach($passenger->getPurchase()->getPayments() as $payment)
//		{
//			if($payment->getRefunded() == false)
//			{
//				//deteremine how many purchases this payment is associated with and divide equally
//				$purchasePaymentTotal += $payment->calculateAmount();
//				// $purchasePaymentTotal = count($payment->getPurchases());
//			}
//		}
//
//		$purchaseTotal = $passenger->getPurchase()->calculatePurchaseTotal();
//		$purchaseBalance = $purchaseTotal - $purchasePaymentTotal;
//
//		$request->attributes->set('passenger', $passenger);
//
//		$form = $this->createFormBuilder($passenger->getPurchase())
//			->add('purchaseItems', 'collection', array('type' => new PurchaseItemType(), 'allow_add' => true, 'allow_delete' => true, 'by_reference' => false))
//			->add('paymentType', 'entity', array('label' => 'Payment Type', 'class' => 'AazpBookingBundle:PaymentType','property' => 'name', 'mapped' => false, 'expanded' => true))
//			->add('paymentAmount', 'number', array('precision' => 2, 'data' => $purchaseBalance, 'mapped' => false))
//			->add('description', 'text', array('mapped' => false, 'required' => false))
//			->add('confirm', 'submit')
//	 		->add('cancel', 'submit', array('attr' => array('formnovalidate' => true, 'data-toggle' => 'modal', 'data-target' => '#cancelWarning', )))
//			->getForm();
//
//		$form->handleRequest($request);
//
//		if ($form->isValid())
//		{
//			$payment = new Payment();
//			$payment->setAmount($form->get('paymentAmount')->getData());
//			$payment->setPaymentType($form->get('paymentType')->getData());
//			$payment->setDescription($form->get('description')->getData());
//
//			$passenger->getPurchase()->addPayment($payment);
//
//			$passenger->getBooking()->setStatus(Booking::STATUS_CONFIRMED);
//
//			$em->persist($passenger->getPurchase());
//			$em->flush();
//			$this->get('session')->getFlashBag()->add('success', 'Payment '.$payment->getAmount().' CHF has been successful!');
//
//	        return $this->redirect($this->generateUrl('booking_show', array ('id'=> $passenger->getBooking()->getId())));
//		}
//
//
//		return $this->render('AazpBookingBundle:Purchase:summary.html.twig', array(
//            'purchaseBalance' => $purchaseBalance,
//            'passenger' => $passenger,
//            'form'	=> $form->createView(),
//			));
//    }
//
    /**
     * @Route("/payment/refund/{id}/{transactionNo}", name="booking_payment_refund")
     */
    public function paymentRefundAction($id, $transactionNo)
    {
        $em = $this->getDoctrine()->getManager();
        $paymentsRepos = $em->getRepository(Payment::class);
        $paymentsRefund = $em->getRepository(Payment::class)->findByTransactionNo($transactionNo);
        if (!$paymentsRefund) {
            throw $this->createNotFoundException('Unable to find Payment entities.');
        }

        $paymentAmount = 0.0;
        foreach ($paymentsRefund as $paymentRefund)
        {
            $paymentRefund->setRefunded(true);
            $paymentAmount = $paymentRefund->getAmount();
        }
        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'Payment '.$paymentAmount.' CHF has been refunded!');

        $booking = $em->getRepository(Booking::class)->find($id);
        if (!$booking)
        {
            throw $this->createNotFoundException('Unable to find Booking entity.');
        }

        $deleteForm = $this->createFormBuilder($booking)
            ->setAction($this->generateUrl('booking_delete', array ('id' => $id)))
            ->add('Delete', SubmitType::class)
            ->getForm();

        $payments = $paymentsRepos->getPaymentsForBooking($booking);
        $paymentViewHelper = new PaymentViewHelper();
        $paymentViewList = $paymentViewHelper->processPayments($payments);

        return $this->render('booking/show.html.twig', array(
            'entity'      => $booking,
            'delete_form' => $deleteForm->createView(),
            'payments' => $payments,
            'paymentViewList' => $paymentViewList,
        ));
    }

//    public function oldPaymentRefundAction($id, $paymentId)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $paymentsRepos = $em->getRepository('AazpBookingBundle:Payment');
//        $payment = $em->getRepository('AazpBookingBundle:Payment')->find($paymentId);
//        if (!$payment) {
//            throw $this->createNotFoundException('Unable to find Payment entities.');
//        }
//
//        $payment->setRefunded(true);
//        $em->flush();
//
//        $booking = $em->getRepository('AazpBookingBundle:Booking')->find($id);
//        if (!$booking) {
//            throw $this->createNotFoundException('Unable to find Booking entity.');
//        }
//
//        $deleteForm = $this->createFormBuilder($booking)
//		            ->setAction($this->generateUrl('booking_delete', array ('id' => $id)))
//		            ->add('Delete', 'submit')
//					->getForm();
//
//
//		$payments = $paymentsRepos->getPaymentsForBooking($booking);
//// 		$payments = new ArrayCollection();
//// 		foreach($booking->getPassengers() as $passenger)
//// 		{
//// 			if($passenger->getPurchase() != null)
//// 			{
//// 				foreach($passenger->getPurchase()->getPayments() as $payment)
//// 				{
//// 					if(!$payments->contains($payment))
//// 					{
//// 						$payments[] = $payment;
//// 					}
//// 				}
//// 			}
//// 		}
//
//        return $this->render('AazpBookingBundle:Booking:show.html.twig', array(
//            'entity'      => $booking,
//            'delete_form' => $deleteForm->createView(),
//            'payments' => $payments,
//        ));
//	}
//

    /**
     * @Route("/product/cost/{id}", name="booking_product_cost")
     */
    public function productCostAction($id)
    {
        $em = $this->getDoctrine()->getManager();
		$product = $em->getRepository(Product::class)->find($id);

	    $json = json_encode($product->getPrice());
	    $response = new Response($json, 200);
	    $response->headers->set('Content-Type', 'application/json');
	    return $response;
	}

//	public function reportDailyPilotPaymentAction()
//	{
//	    $em = $this->getDoctrine()->getManager();
//	    $request = Request::createFromGlobals();
//	    $pilots = $em->getRepository('AazpBookingBundle:Pilot')->findAll();
//	    $passengerRepos = $em->getRepository('AazpBookingBundle:Passenger');
//	    $pilotFlightCommissionRepository = $em->getRepository('AazpBookingBundle:PilotFlightCommission');
//
//	    $targetDateStr = $request->query->get('date', '');
//	    $targetDate = new \DateTime($targetDateStr);
//
//	    $endOfDayReport = new EndOfDayReport($targetDate);
//	    foreach($pilots as $pilot)
//	    {
//	        $endOfDayReportPilot = new EndOfDayReportPilot($pilot);
//	        $endOfDayReport->addPilot($endOfDayReportPilot);
//
//	        $passengers = $passengerRepos->getPassengersForPilotOnADate($pilot, $targetDate);
//
//	        foreach ($passengers as $passenger)
//	        {
//	            $booking = $passenger->getBooking();
//	            $flightPurchaseItem = null;
//	            $photoPurchaseItem = null;
//	            $creditCardFeePurchaseItem = null;
//	            $pilotFlightCommission = null;
//	            if($passenger->getPurchase() != null)
//	            {
//    	            foreach ($passenger->getPurchase()->getPurchaseItems() as $purchaseItem)
//    	            {
//    	                if($purchaseItem->getProduct()->getProductCategory()->getName() == "FLIGHT")
//    	                {
//    	                    $flightPurchaseItem = $purchaseItem;
//    	                }
//    	                if($purchaseItem->getProduct()->getProductCategory()->getName() == "FLIGHT-PHOTO")
//    	                {
//    	                    $photoPurchaseItem = $purchaseItem;
//    	                }
//    	                if($purchaseItem->getProduct()->getProductCategory()->getName() == "CARD-FEE")
//    	                {
//    	                    $creditCardFeePurchaseItem = $purchaseItem;
//    	                }
//                    }
//                    $cardPayment = false;
//                    $invoicePayment = false;
//                    $voucherPayment = false;
//                    $sumUpPayment = false;
//                    foreach ($passenger->getPurchase()->getPayments() as $payment)
//                    {
//                        if(!$payment->getRefunded())
//                        {
//                            if ($payment->getPaymentType()->getName() == 'VISA' ||
//                                $payment->getPaymentType()->getName() == 'M/C' ||
//                                $payment->getPaymentType()->getName() == 'JCB' ||
//                                $payment->getPaymentType()->getName() == 'AMEX' ||
//                                $payment->getPaymentType()->getName() == 'MAESTRO')
//                            {
//                                $cardPayment = true;
//                            }
//                            if($payment->getPaymentType()->getName() == 'INVOICE')
//                            {
//                                $invoicePayment = true;
//                            }
//                            if($payment->getPaymentType()->getName() == 'VOUCHER')
//                            {
//                                $voucherPayment = true;
//                            }
//                        }
//                    }
//
//                $flight = $flightPurchaseItem->getProduct();
//                $pilotFlightCommission = $pilotFlightCommissionRepository->getPilotFlightCommission($pilot, $flight);
//	           }
//
//                if($pilotFlightCommission === null)
//                {
//                    $commission = 0.0;
//                }
//                else
//                {
//                    $commission = $pilotFlightCommission->getFlightCommission();
//                }
//
//	            $endOfDayReportPilotBooking = new EndOfDayReportPilotBooking($booking, $passenger, $flightPurchaseItem, $photoPurchaseItem, $creditCardFeePurchaseItem, $cardPayment, $invoicePayment, $voucherPayment, $commission);
//	            $endOfDayReportPilot->addPilotBooking($endOfDayReportPilotBooking);
//            }
//
//	    }
//	    return $this->render('AazpBookingBundle:Report:daily.pilot.payment.html.twig', array(
//	        'endOfDayReport'	=> $endOfDayReport));
//    }
//
//	public function reportDailyPilotPaymentOLDAction()
//	{
//        $em = $this->getDoctrine()->getManager();
//    	$request = Request::createFromGlobals();
//
//		$date = new \DateTime();
//    	$targetDateStr = $request->query->get('date', '');
//		$targetDate = new \DateTime($targetDateStr);
//		$enddate = clone $targetDate;
//		$enddate = $enddate->modify('+ 1 days');
//
//		$pilot_summary = new ArrayCollection();
//        $pilots = $em->getRepository('AazpBookingBundle:Pilot')->findAll();
//
//		foreach( $pilots as $pilot)
//		{
//			$query = $em->createQuery("SELECT p FROM Aazp\BookingBundle\Entity\Passenger p
//											LEFT JOIN p.pilot pi
//											LEFT JOIN p.flight f
//											LEFT JOIN p.purchase pu
//											JOIN p.booking b
//											WHERE b.flightdate >= :startdate
//											AND b.flightdate < :enddate
//											AND pi.id = :pilot
//											ORDER BY b.flightdate
//											");
//			$query->setParameter('pilot', $pilot->getId());
//			$query->setParameter('startdate', $targetDate);
//			$query->setParameter('enddate', $enddate);
//
//
//			$passengers = $query->getResult();
//
//			foreach ($passengers as $passenger)
//			{
//				$pilot = $passenger->getPilot();
//				$commission = $pilot->calculateCommissionRate($targetDate);
//				$pilot->setCommissionRate($commission);
//			}
//
//
//
//			$pilot_summary[] = $passengers;
//		}
//
//		return $this->render('AazpBookingBundle:Report:daily1.pilot.payment.html.twig', array(
//			'report_date' => $targetDate,
//			'pilot_summary'	=> $pilot_summary));
//	}
//

}
